<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\Sendgrid;

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
            throw new ProviderRateLimitException([], 'Too many requests');
        }

        if (in_array($response->status(), [202])) {
            return $subscriber = Responder::for($response)->getVerificationPendingSubscriber($subscriber);
        }

        if (in_array($response->status(), [200, 201, 202])) {
            return Responder::for($response)->updateSubscriber($subscriber);
        }


        throw new CannotAddSubscriberException([], 'Something went wrong');
    }

    public function blabla (SubscriberNotVerified $subscriber)
    {
        dump('__--_---__---');
        dump($subscriber);

        $url = $this->endpoint . '/v3/marketing/contacts/imports/' . $subscriber->getJobId();


        while (true) {
            $response = $this->requestWithHeaders()->get($url);
            dump('NEXT TRY');
            dump($response->json());
            $status = $response->json()['status'];
            if ($status === 'completed') {
                break;
            }
            sleep(5);
        }

        dump('FINALLY!!!!!');
        dump($response->json());
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

        throw new CannotGetSubscriberException([], "Something went wrong");
    }


    /**
     * @throws CannotAddSubscriberToMailingListException
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
            throw new ProviderRateLimitException([], 'Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException([], 'Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     *
     * @url https://docs.sendgrid.com/api-reference/lists/remove-contacts-from-a-list
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/v3/marketing/lists/' .$subscriberList->id . '/contacts';

        $response = $this->requestWithHeaders()->delete($url);
        dump($response);
        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromMailingListException(
                [
                    'subscriber' => $subscriber,
                    'mailingList' => $subscriberList
                ],
                'Subscriber not found in mailing list'
            );
        }

        if ($response->status() === 202) {
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

