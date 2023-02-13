<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\GetResponse;

use App\Exceptions\Services\SubscriberManager\CannotAddSubscriberToSubscriberListException;
use App\Exceptions\Services\SubscriberManager\CannotDeleteSubscriberFromSubscriberListException;
use App\Exceptions\Services\SubscriberManager\CannotGetSubscriberException;
use App\Exceptions\Services\SubscriberManager\ProviderRateLimitException;
use App\Exceptions\Services\SubscriberManager\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Services\SubscriberManager\SubscriberNotFoundException;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\BaseDeliveryService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeliveryService extends BaseDeliveryService implements MailDeliveryServiceInterface
{
    const TYPE = 'GETRESPONSE';
    protected string $type = self::TYPE;

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
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array
    {
        $url = $this->endpoint . '/campaigns';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getSubscriberLists();
    }

    /**
     * @throws SubscriberAddingIsNotSupportedException
     */
    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        throw new SubscriberAddingIsNotSupportedException(
            ['subscriber' => $subscriber],
            'GetResponse requires campaign to add subscriber'
        );
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     */
    public function verifySubscriber(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList = null): SubscriberInterface
    {
        $url = $this->endpoint . '/campaigns/'.$subscriberList->id.'/contacts?query[email]=' . $subscriber->email->get();

        $response = $this->requestWithHeaders()->get($url);

        if ($response->status() === 404) {
            throw new SubscriberNotFoundException([], 'Subscriber Not Found');
        }

        if ($response->status() === 200) {
            return Responder::for($response)->updateSubscriber($subscriber);
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
        $url = $this->endpoint . '/contacts';

        $data = [
            'email' => $subscriber->email->get(),
            'campaign' => [
                'campaignId' => $subscriberList->id,
            ],
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        if (in_array($response->status(), [202])) {
            return Responder::for($response)->updateSubscriberAfterAddToSubscriberList($subscriber, $subscriberList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException([
                'subscriber' => $subscriber->toArray(),
                'subscriberList' => $subscriberList->toArray()
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
        $url = $this->endpoint . '/contacts/'. $subscriber->id;

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromSubscriberListException([
                    'subscriber' => $subscriber->toArray(),
                    'subscriberList' => $subscriberList->toArray()
                ],
                'Subscriber Not Found'
            );
        }

        if ($response->status() === 204) {
            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        }

        throw new CannotDeleteSubscriberFromSubscriberListException([
            'subscriber' => $subscriber->toArray(),
            'subscriberList' => $subscriberList->toArray(),
        ]);
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

