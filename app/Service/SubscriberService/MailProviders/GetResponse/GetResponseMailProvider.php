<?php

namespace App\Service\SubscriberService\MailProviders\GetResponse;

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

class GetResponseMailProvider extends BaseMailProvider implements MailProviderInterface
{
    const MAIL_PROVIDER_TYPE = 'GETRESPONSE';
    protected string $mailProviderType = self::MAIL_PROVIDER_TYPE;

    protected string $endpoint = 'https://api.getresponse.com/v3';
    protected string $testGroupId = 'TEST_GETRESPONSE_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_GETRESPONSE_SECOND_GROUP_ID';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/accounts';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array
    {
        $url = $this->endpoint . '/campaigns';

        $response = $this->requestWithHeaders()->get($url);

        return GetResponseResponderMail::for($response)->getMailingLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     */
    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/contacts';

        $data = [
            'email' => $subscriber->getEmail(),
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        if (in_array($response->status(), [200, 201, 202])) {
            return GetResponseResponderMail::for($response)->getVerifiedSubscriber($subscriber);
        }

        throw new CannotAddSubscriberException('Something went wrong');
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     */
    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified  $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/subscribers/' . $subscriber->getEmail();

        $response = $this->requestWithHeaders()->get($url);

        if (in_array($response->status(), [404])) {
            throw new SubscriberNotFoundException('Subscriber Not Found');
        }

        if (in_array($response->status(), [200])) {
            return GetResponseResponderMail::for($response)->getVerifiedSubscriber($subscriber);
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
    public function addSubscriberVerifiedToMailingList(SubscriberDraft|SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $url = $this->endpoint . '/contacts';

        $data = [
            'email' => $subscriber->getEmail(),
            'name' => $subscriber->getFirstName(),
            'campaign' => [
                'campaignId' => $mailingList->getId(),
            ],
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        if (in_array($response->status(), [202])) {
            return GetResponseResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
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
        $url = $this->endpoint . '/subscribers/'. $subscriber->getId() . '/groups/'. $mailingList->getId();

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromMailingListException('Subscriber not found in mailing list', ['subscriber' => $subscriber, 'mailingList' => $mailingList]);
        }

        if ($response->status() === 204) {
            GetResponseResponderMail::for($response)->getVerifiedSubscriberAfterDeleteFromMailingList($subscriber, $mailingList);

            return $subscriber;
        }

        throw new CannotDeleteSubscriberFromMailingListException('Something went wrong');
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'X-Auth-Token' => 'api-key ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

