<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\Sendgrid;

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
    const TYPE = 'SENDGRID';
    protected string $type = self::TYPE;

    protected string $endpoint = 'https://api.sendgrid.com';
    protected string $testGroupId = 'TEST_SENDGRID_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_SENDGRID_SECOND_GROUP_ID';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/v3/marketing/lists';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return SubscriberListInterface[]
     *
     * @url https://docs.sendgrid.com/api-reference/lists/get-all-lists
     */
    public function getSubscriberLists(): array
    {
        $url = $this->endpoint . '/v3/marketing/lists';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getSubscriberLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     *
     * @see https://docs.sendgrid.com/api-reference/contacts/add-or-update-a-contact
     */
    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $url = $this->endpoint . '/v3/marketing/contacts';

        $contacts = json_decode(json_encode([
            'contacts' => [[
                'email' => $subscriber->email->get(),
                'first_name' => $subscriber->firstName->get(),
                'last_name' => $subscriber->lastName->get(),
            ]],
        ]));

        $response = $this->requestWithHeaders()->put($url, $contacts);

        if ($response->status() === 429) {
            throw new ProviderRateLimitException([
                'subscriber' => $subscriber->toArray(),
            ]);
        }

        if (in_array($response->status(), [202])) {
            return $subscriber = Responder::for($response)->getVerificationPendingSubscriber($subscriber);
        }

        if (in_array($response->status(), [200, 201, 202])) {
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
        $url = $this->endpoint . '/v3/marketing/contacts/search/emails';

        $data = json_decode('{"emails": ["'.$subscriber->email->get().'"]}');

        $response = $this->requestWithHeaders()->post($url, $data);

        if ($response->status() === 404) {
            throw new SubscriberNotFoundException([], "Subscriber Not Found");
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
     *
     * @url https://docs.sendgrid.com/api-reference/contacts/add-or-update-a-contact
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/v3/marketing/contacts';

        $data = json_decode('{"contacts": [{"email": "'.$subscriber->email->get().'"}], "list_ids": ["'.$subscriberList->id.'"]}');

        $response = $this->requestWithHeaders()->put($url, $data);

        if ($response->status() === 202) {
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
     *
     * @url https://docs.sendgrid.com/api-reference/lists/remove-contacts-from-a-list
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/v3/marketing/lists/' .$subscriberList->id . '/contacts';

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromSubscriberListException([
                    'subscriber' => $subscriber->toArray(),
                    'subscriberList' => $subscriberList->toArray()
                ],
                'Subscriber not found'
            );
        }

        if ($response->status() === 202) {
            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        }

        throw new CannotDeleteSubscriberFromSubscriberListException([
                'subscriber' => $subscriber->toArray(),
                'subscriberList' => $subscriberList->toArray(),
            ],
        );
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

