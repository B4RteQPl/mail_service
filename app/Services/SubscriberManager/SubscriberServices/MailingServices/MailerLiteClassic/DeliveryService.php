<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\MailerLiteClassic;

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

    const TYPE = 'MAILERLITE_CLASSIC';
    protected string $type = self::TYPE;

    protected string $endpoint = 'https://api.mailerlite.com/api/v2';
    protected string $testGroupId = 'TEST_MAILERLITE_CLASSIC_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_MAILERLITE_CLASSIC_SECOND_GROUP_ID';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/me';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array
    {
        $url = $this->endpoint . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getSubscriberLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     */
    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $url = $this->endpoint . '/subscribers';

        $data = [
            'email' => $subscriber->email->get(),
            'fields' => [
                'first_name' => $subscriber->firstName->get(),
                'last_name' => $subscriber->lastName->get(),
            ]
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

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
        $url = $this->endpoint . '/subscribers/' . $subscriber->email->get();

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
        $url = $this->endpoint . '/groups/group_name/subscribers/' . $subscriber->id . '/assign';
        $data = ['group_name' => $subscriberList->name];

        $response = $this->requestWithHeaders()->post($url, $data);

        if ($response->status() === 200) {
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
        $url = $this->endpoint . '/groups/'. $subscriberList->id . '/subscribers/'. $subscriber->id;

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromSubscriberListException([
                    'subscriber' => $subscriber->toArray(),
                    'subscriberList' => $subscriberList->toArray(),
                ],
                'Cannot delete subscriber, because is not assigned to mailing list'
            );
        }

        if ($response->status() === 204) {
            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        }

        throw new CannotDeleteSubscriberFromSubscriberListException([
            'subscriber' => $subscriber->toArray(),
            'subscriberList' => $subscriberList->toArray()
        ]);
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'X-MailerLite-ApiKey' => $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

