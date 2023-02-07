<?php

namespace App\Service\SubscriberService\MailProviders\MailChimp;

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

class MailChimpMailProvider extends BaseMailProvider implements MailProviderInterface
{
    const MAIL_PROVIDER_TYPE = 'MAILCHIMP';
    protected string $mailProviderType = self::MAIL_PROVIDER_TYPE;

    protected string $endpoint;
    protected string $testGroupId = 'TEST_MAILCHIMP_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_MAILCHIMP_SECOND_GROUP_ID';

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

        return MailChimpResponderMail::for($response)->getMailingLists();
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
            return MailChimpResponderMail::for($response)->getVerifiedSubscriber($subscriber);
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
            return MailChimpResponderMail::for($response)->getVerifiedSubscriberFromSearchResult($subscriber);
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
            return MailChimpResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
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
        ]);

        $response = $this->requestWithHeaders()->post($url, $contactList);

        if (in_array($response->status(), [200, 201])) {
            return MailChimpResponderMail::for($response)->getVerifiedSubscriberAfterDeleteFromMailingList($subscriber, $mailingList);
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

