<?php

namespace App\Service\SubscriberService\MailProviders\MailerLite;

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

class MailerLiteMailProvider extends BaseMailProvider implements MailProviderInterface
{
    const MAIL_PROVIDER_TYPE = 'MAILERLITE';
    protected string $mailProviderType = self::MAIL_PROVIDER_TYPE;

    protected string $endpoint = 'https://connect.mailerlite.com/api';
    protected string $testGroupId = 'TEST_MAILERLITE_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_MAILERLITE_SECOND_GROUP_ID';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array
    {
        $url = $this->endpoint . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return MailerLiteResponderMail::for($response)->getMailingLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     */
    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/subscribers';

        $data = [
            'email' => $subscriber->getEmail(),
            'fields' => [
                'first_name' => $subscriber->getFirstName(),
                'last_name' => $subscriber->getLastName(),
            ]
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        if (in_array($response->status(), [200, 201])) {
            return MailerLiteResponderMail::for($response)->getVerifiedSubscriber($subscriber);
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
            return MailerLiteResponderMail::for($response)->getVerifiedSubscriber($subscriber);
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
        $url = $this->endpoint . '/subscribers/' . $subscriber->getId() . '/groups/' . $mailingList->getId();

        $response = $this->requestWithHeaders()->post($url);

        if (in_array($response->status(), [200, 201])) {
            return MailerLiteResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
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
            MailerLiteResponderMail::for($response)->getVerifiedSubscriberAfterDeleteFromMailingList($subscriber, $mailingList);

            return $subscriber;
        }

        throw new CannotDeleteSubscriberFromMailingListException('Something went wrong');
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

