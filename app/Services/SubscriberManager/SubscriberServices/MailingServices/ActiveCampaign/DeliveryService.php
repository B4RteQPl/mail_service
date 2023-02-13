<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\ActiveCampaign;

use App\Exceptions\Services\SubscriberManager\CannotAddSubscriberException;
use App\Exceptions\Services\SubscriberManager\CannotAddSubscriberToSubscriberListException;
use App\Exceptions\Services\SubscriberManager\CannotDeleteSubscriberFromSubscriberListException;
use App\Exceptions\Services\SubscriberManager\CannotGetSubscriberException;
use App\Exceptions\Services\SubscriberManager\ProviderRateLimitException;
use App\Exceptions\Services\SubscriberManager\SubscriberNotFoundException;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\BaseDeliveryService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeliveryService extends BaseDeliveryService implements MailDeliveryServiceInterface
{
    const TYPE = 'ACTIVECAMPAIGN';
    const STATUS_SUBSCRIBED = '1';
    const STATUS_UNSUBSCRIBED = '2';
    protected string $type = self::TYPE;

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
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array
    {
        $url = $this->endpoint . '/lists';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getSubscriberLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     */
    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $url = $this->endpoint . '/contacts';

        $contact = array('contact' => [
            'email' => $subscriber->email->get(),
            'firstName' => $subscriber->firstName->get(),
            'lastName' => $subscriber->lastName->get(),
        ]);

        $response = $this->requestWithHeaders()->post($url, $contact);

        if ($response->status() === 429) {
            throw new ProviderRateLimitException([
                'subscriber' => $subscriber->toArray(),
            ]);
        }

        if (in_array($response->status(), [200, 201])) {
            return Responder::for($response)->updateSubscriber($subscriber);
        }

        throw new CannotAddSubscriberException([], 'Something went wrong');
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     */
    public function verifySubscriber(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList = null): SubscriberInterface
    {
        $url = $this->endpoint . '/contacts?email=' . $subscriber->email->get();

        $response = $this->requestWithHeaders()->get($url);

        if ($response->status() === 404) {
            throw new SubscriberNotFoundException([], 'Subscriber Not Found');
        }

        if ((int) $response->json()['meta']['total'] === 0) {
            throw new SubscriberNotFoundException([], 'Subscriber Not Found');
        }

        if (in_array($response->status(), [200, 201])) {
            return Responder::for($response)->updateSubscriberFromSearchResult($subscriber);
        }

        $debugData = [
            'subscriber' => $subscriber->toArray(),
        ];
        if ($subscriberList) {
            $debugData['subscriberList'] = $subscriberList->toArray();
        }
        throw new CannotGetSubscriberException($debugData);
    }

    /**
     * @throws CannotAddSubscriberToSubscriberListException
     * @throws ProviderRateLimitException
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/contactLists';

        $contactList = array('contactList' => [
            'list' => $subscriberList->id,
            'contact' => $subscriber->id,
            'status' => self::STATUS_SUBSCRIBED,
        ]);

        $response = $this->requestWithHeaders()->post($url, $contactList);

        if (in_array($response->status(), [200, 201])) {
            return Responder::for($response)->updateSubscriberAfterAddToSubscriberList($subscriber, $subscriberList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException([
                'subscriber' => $subscriber->toArray(),
                'subscriberList' => $subscriberList->toArray(),
            ]);
        }

        throw new CannotAddSubscriberToSubscriberListException([
            'subscriber' => $subscriber->toArray(),
            'subscriberList' => $subscriberList->toArray()
        ]);
    }

    /**
     * @throws CannotDeleteSubscriberFromSubscriberListException
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/contactLists';

        $contactList = array('contactList' => [
            'list' => $subscriberList->id,
            'contact' => $subscriber->id,
            'status' => self::STATUS_UNSUBSCRIBED,
        ]);

        $response = $this->requestWithHeaders()->post($url, $contactList);

        if (in_array($response->status(), [200, 201])) {
            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException([
                'subscriber' => $subscriber->toArray(),
                'subscriberList' => $subscriberList->toArray(),
            ]);
        }

        throw new CannotDeleteSubscriberFromSubscriberListException([
            'subscriber' => $subscriber->toArray(),
            'subscriberList' => $subscriberList->toArray()
        ]);
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

