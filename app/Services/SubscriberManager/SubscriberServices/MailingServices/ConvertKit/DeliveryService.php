<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\ConvertKit;

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

/**
 * ConvertKit uses the concept of "tags" to categorize subscribers into lists.
 * Keep in mind that during reading code because technically Mailing list is a tag in ConvertKit.
 */
class DeliveryService extends BaseDeliveryService implements MailDeliveryServiceInterface
{
    const TYPE = 'CONVERTKIT';
    protected string $type = self::TYPE;

    protected string $endpoint = 'https://api.convertkit.com/v3';
    protected string $testGroupId = 'TEST_CONVERTKIT_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_CONVERTKIT_SECOND_GROUP_ID';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/account' . $this->getAuthParams();

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return SubscriberListInterface[]
     *
     * @url https://developers.convertkit.com/#list-tags
     */
    public function getSubscriberLists(): array
    {
        $url = $this->endpoint . '/tags' . $this->getAuthParams();

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
            'ConvertKit requires tags to add subscriber'
        );
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     *
     * Verified subscriber is searched by email
     * @url https://developers.convertkit.com/#list-subscribers
     */
    public function verifySubscriber(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList = null): SubscriberInterface
    {
        $url = $this->endpoint . '/subscribers' . $this->getAuthParams() . '&email_address=' . $subscriber->email->get();

        $response = $this->requestWithHeaders()->get($url);

        dump($response->json());
        if ($response->json() && $response->json()['total_subscribers'] && $response->json()['total_subscribers'] === 0) {
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
     * @url https://developers.convertkit.com/#tag-a-subscriber
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/tags/' . $subscriberList->id . '/subscribe' . $this->getAuthParams() . '&email=' . $subscriber->email->get();

        $response = $this->requestWithHeaders()->post($url, []);

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
     * @url https://developers.convertkit.com/#remove-tag-from-a-subscriber
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/subscribers/'. $subscriber->id . '/tags/'. $subscriberList->id . $this->getAuthParams();

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

        if (in_array($response->status(), [200, 204])) {
            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        }

        throw new CannotDeleteSubscriberFromMailingListException([], 'Something went wrong');
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'charset' => 'utf-8',
        ]);
    }

    private function getAuthParams()
    {
        return '?api_secret=' . $this->authKey;
    }
}

