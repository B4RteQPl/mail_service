<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\MailerLite;

use App\Exceptions\Service\SubscriberService\CannotAddSubscriberException;
use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\ProviderRateLimitException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\BaseDeliveryService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeliveryService extends BaseDeliveryService implements MailDeliveryServiceInterface
{
    const TYPE = 'MAILERLITE';
    protected string $type = self::TYPE;

    protected string $endpoint = 'https://connect.mailerlite.com/api';
    protected string $testGroupId = 'TEST_MAILERLITE_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_MAILERLITE_SECOND_GROUP_ID';


    /**
     * @url https://developers.mailerlite.com/docs/groups.html#list-all-groups
     */
    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @url https://developers.mailerlite.com/docs/groups.html#list-all-groups
     *
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
     *
     * @url https://developers.mailerlite.com/docs/subscribers.html#create-upsert-subscriber
     */
    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $url = $this->endpoint . '/subscribers';

        $data = [
            'email' => $subscriber->email->get(),
//            'fields' => [
//                'first_name' => $subscriber->firstName->get(),
//                'last_name' => $subscriber->lastName->get(),
//            ]
        ];

        $response = $this->requestWithHeaders()->post($url, $data);
        dump($response->json());
        if ($response->status() === 429) {
            throw new ProviderRateLimitException([], 'Too many requests');
        }

        if (in_array($response->status(), [200, 201])) {
            return Responder::for($response)->updateSubscriber($subscriber);
        }

        throw new CannotAddSubscriberException([], 'Something went wrong');
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     *
     * @url https://developers.mailerlite.com/docs/subscribers.html#fetch-a-subscriber
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

        throw new CannotGetSubscriberException([], 'Something went wrong');
    }

    /**
     * @throws CannotAddSubscriberToMailingListException
     * @throws ProviderRateLimitException
     *
     * @url https://developers.mailerlite.com/docs/groups.html#assign-subscriber-to-a-group
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        dump('???');
        $url = $this->endpoint . '/subscribers/' . $subscriber->id . '/groups/' . $subscriberList->id;
//        $data = ['group_name' => $subscriberList->name];
        $response = $this->requestWithHeaders()->post($url);
        dump('?-----??');
        dump($response);
        dump($response->status());
        dump($response->json());
        dump('?=====??');
//        dump($response->json());
        if (in_array($response->status(), [200, 201])) {
            return Responder::for($response)->updateSubscriberAfterAddToSubscriberList($subscriber, $subscriberList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException([], 'Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException([], 'Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     *
     * @url https://developers.mailerlite.com/docs/groups.html#unassign-subscriber-from-a-group
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/subscribers/'. $subscriber->id . '/groups/'. $subscriberList->id;

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromMailingListException(
                [
                    'subscriber' => $subscriber,
                    'mailingList' => $subscriberList
                ],
                'Subscriber not found in mailing list'
            );
        }

        if ($response->status() === 204) {
            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        }

        throw new CannotDeleteSubscriberFromMailingListException([], 'Something went wrong');
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

