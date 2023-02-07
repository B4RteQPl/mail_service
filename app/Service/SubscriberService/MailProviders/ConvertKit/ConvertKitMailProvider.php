<?php

namespace App\Service\SubscriberService\MailProviders\ConvertKit;

use App\Exceptions\Service\SubscriberService\CannotAddSubscriberException;
use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\ProviderRateLimitException;
use App\Exceptions\Service\SubscriberService\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\MailProviders\BaseMailProvider;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * ConvertKit uses the concept of "tags" to categorize subscribers into lists.
 * Keep in mind that during reading code because technically Mailing list is a tag in ConvertKit.
 */
class ConvertKitMailProvider extends BaseMailProvider implements MailProviderInterface
{
    const MAIL_PROVIDER_TYPE = 'CONVERTKIT';
    protected string $mailProviderType = self::MAIL_PROVIDER_TYPE;

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
     * @return MailingList[]
     * @url https://developers.convertkit.com/#list-tags
     */
    public function getMailingLists(): array
    {
        $url = $this->endpoint . '/tags' . $this->getAuthParams();

        $response = $this->requestWithHeaders()->get($url);

        return ConvertKitResponderMail::for($response)->getMailingLists();
    }

    /**
     * IMPORTANT: You must add the subscriber to a form, sequence, or tag
     * @throws SubscriberAddingIsNotSupportedException
     */
    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        throw new SubscriberAddingIsNotSupportedException('ConvertKit does not support adding subscribers without adding them to a form, sequence, or tag');
    }

    /**
     * @throws CannotGetSubscriberException
     * @throws SubscriberNotFoundException
     *
     * Verified subscriber is searched by email
     * @url https://developers.convertkit.com/#list-subscribers
     */
    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/subscribers' . $this->getAuthParams() . '&email_address=' . $subscriber->getEmail();

        $response = $this->requestWithHeaders()->get($url);

        if ($response->json()['total_subscribers'] === 0) {
            throw new SubscriberNotFoundException('Subscriber Not Found');
        }

        if (in_array($response->status(), [200])) {
            return ConvertKitResponderMail::for($response)->getVerifiedSubscriber($subscriber);
        }

        throw new CannotGetSubscriberException('Something went wrong');
    }

    /**
     * @throws CannotAddSubscriberToMailingListException
     * @throws ProviderRateLimitException
     *
     * @url https://developers.convertkit.com/#tag-a-subscriber
     */
    public function addSubscriberDraftToMailingList(SubscriberDraft $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $url = $this->endpoint . '/tags/' . $mailingList->getId() . '/subscribe' . $this->getAuthParams() . '&email=' . $subscriber->getEmail();

        $response = $this->requestWithHeaders()->post($url, []);

        if (in_array($response->status(), [200, 201])) {
            return ConvertKitResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException('Something went wrong');
    }

    /**
     * @throws CannotAddSubscriberToMailingListException
     * @throws ProviderRateLimitException
     *
     * @url https://developers.convertkit.com/#tag-a-subscriber
     */
    public function addSubscriberVerifiedToMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $url = $this->endpoint . '/tags/' . $mailingList->getId() . '/subscribe' . $this->getAuthParams() . '&email=' . $subscriber->getEmail();

        $response = $this->requestWithHeaders()->post($url, []);

        if (in_array($response->status(), [200, 201])) {
            return ConvertKitResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException('Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     *
     * @url https://developers.convertkit.com/#remove-tag-from-a-subscriber
     */
    public function deleteSubscriberFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $url = $this->endpoint . '/subscribers/'. $subscriber->getId() . '/tags/'. $mailingList->getId() . $this->getAuthParams();

        $response = $this->requestWithHeaders()->delete($url);
        dump($response->status());
        dump($response->json());
        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromMailingListException('Subscriber not found in mailing list', ['subscriber' => $subscriber, 'mailingList' => $mailingList]);
        }

        if (in_array($response->status(), [200, 204])) {
            ConvertKitResponderMail::for($response)->getVerifiedSubscriberAfterDeleteFromMailingList($subscriber, $mailingList);

            return $subscriber;
        }

        throw new CannotDeleteSubscriberFromMailingListException('Something went wrong');
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

