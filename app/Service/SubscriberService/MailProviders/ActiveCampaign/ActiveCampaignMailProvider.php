<?php

namespace App\Service\SubscriberService\MailProviders\ActiveCampaign;

use App\Exceptions\Service\SubscriberService\CannotAddSubscriberException;
use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\ProviderRateLimitException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\MailProviders\BaseMailProvider;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ActiveCampaignMailProvider extends BaseMailProvider implements MailProviderInterface
{
    const MAIL_PROVIDER_TYPE = 'ACTIVECAMPAIGN';
    const STATUS_SUBSCRIBED = '1';
    const STATUS_UNSUBSCRIBED = '2';
    protected string $mailProviderType = self::MAIL_PROVIDER_TYPE;

    protected string $endpoint;
    protected string $testGroupId = 'TEST_ACTIVECAMPAIGN_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_ACTIVECAMPAIGN_SECOND_GROUP_ID';

    public function __construct(string $authKey, string $apiUrl = null)
    {
        parent::__construct($authKey, $apiUrl);

        $this->endpoint = $apiUrl . '/api/3';
    }

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/lists';
        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array
    {
        $url = $this->endpoint . '/lists';

        $response = $this->requestWithHeaders()->get($url);

        return ActiveCampaignResponderMail::for($response)->getMailingLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     */
    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/contacts';

        $contact = array('contact' => [
            'email' => $subscriber->getEmail(),
            'firstName' => $subscriber->getFirstName(),
            'lastName' => $subscriber->getLastName(),
        ]);

        $response = $this->requestWithHeaders()->post($url, $contact);

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        if (in_array($response->status(), [200, 201])) {
            return ActiveCampaignResponderMail::for($response)->getVerifiedSubscriber($subscriber);
        }

        throw new CannotAddSubscriberException('Something went wrong');
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     */
    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified  $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/contacts?email=' . $subscriber->getEmail();

        $response = $this->requestWithHeaders()->get($url);

        if (in_array($response->status(), [404])) {
            throw new SubscriberNotFoundException('Subscriber Not Found');
        }

        if ((int) $response->json()['meta']['total'] === 0) {
            throw new SubscriberNotFoundException('Subscriber Not Found');
        }

        if (in_array($response->status(), [200, 201])) {
            return ActiveCampaignResponderMail::for($response)->getVerifiedSubscriberFromSearchResult($subscriber);
        }

        throw new CannotGetSubscriberException('Something went wrong');
    }

    /**
     * @throws CannotAddSubscriberToMailingListException
     */
    public function addSubscriberDraftToMailingList(SubscriberDraft $subscriber, MailingList $mailingList): SubscriberVerified
    {
        throw new CannotAddSubscriberToMailingListException('Adding subscriber draft is not supported', [
            'subscriber' => $subscriber,
            'mailingList' => $mailingList
        ]);
    }
    /**
     * @throws CannotAddSubscriberToMailingListException
     * @throws ProviderRateLimitException
     */
    public function addSubscriberVerifiedToMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $url = $this->endpoint . '/contactLists';

        $contactList = array('contactList' => [
            'list' => $mailingList->getId(),
            'contact' => $subscriber->getId(),
            'status' => self::STATUS_SUBSCRIBED,
        ]);

        $response = $this->requestWithHeaders()->post($url, $contactList);

        if (in_array($response->status(), [200, 201])) {
            return ActiveCampaignResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException('Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     */
    public function deleteSubscriberFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $url = $this->endpoint . '/contactLists';

        $contactList = array('contactList' => [
            'list' => $mailingList->getId(),
            'contact' => $subscriber->getId(),
            'status' => self::STATUS_UNSUBSCRIBED,
        ]);

        $response = $this->requestWithHeaders()->post($url, $contactList);

        if (in_array($response->status(), [200, 201])) {
            return ActiveCampaignResponderMail::for($response)->getVerifiedSubscriberAfterDeleteFromMailingList($subscriber, $mailingList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        throw new CannotDeleteSubscriberFromMailingListException('Something went wrong');
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'Api-Token' => $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

