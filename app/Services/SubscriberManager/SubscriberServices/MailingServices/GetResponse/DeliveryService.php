<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\GetResponse;

use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\ProviderRateLimitException;
use App\Exceptions\Service\SubscriberService\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
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
        dump($response);
        dump($response->json());
        dump($response->status());
        if ($response->status() === 404) {
            dump('a');
            throw new SubscriberNotFoundException([], 'Subscriber Not Found');
        }

        if ($response->status() === 200) {
            // search has been successful but subscriber still can be not found
            // if subscriber has been added then it's processed.
            // if subscriber not exists then still same result
            //            if (empty($response->json())) {
            //                throw new SubscriberNotFoundException([], 'Subscriber Not Found');
            //            }
            return Responder::for($response)->updateSubscriber($subscriber);
        }

        throw new CannotGetSubscriberException([], 'Something went wrong');
    }

    /**
     * @throws CannotAddSubscriberToMailingListException
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
            throw new ProviderRateLimitException([], 'Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException([], 'Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/contacts/'. $subscriber->id;

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
            'X-Auth-Token' => 'api-key ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

