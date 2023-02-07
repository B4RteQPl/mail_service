<?php

namespace App\Service\SubscriberService\MailProviders\Sendgrid;

use App\Exceptions\Service\SubscriberService\CannotAddSubscriberException;
use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\ProviderRateLimitException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\MailProviders\BaseMailProvider;
use App\Service\SubscriberService\Subscriber\SubscriberAccepted;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SendgridMailProvider extends BaseMailProvider implements MailProviderInterface
{
    const MAIL_PROVIDER_TYPE = 'SENDGRID';
    protected string $mailProviderType = self::MAIL_PROVIDER_TYPE;

    protected string $endpoint = 'https://api.sendgrid.com';
    protected string $testGroupId = 'TEST_SENDGRID_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_SENDGRID_SECOND_GROUP_ID';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    /**
     * @return MailingList[]
     *
     * @url https://docs.sendgrid.com/api-reference/lists/get-all-lists
     */
    public function getMailingLists(): array
    {
        $url = $this->endpoint . '/v3/marketing/lists';

        $response = $this->requestWithHeaders()->get($url);

        return SendgridResponderMail::for($response)->getMailingLists();
    }

    /**
     * @throws ProviderRateLimitException
     * @throws CannotAddSubscriberException
     *
     * @see https://docs.sendgrid.com/api-reference/contacts/add-or-update-a-contact
     */
    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/v3/marketing/contacts';

        $contacts = json_decode(json_encode([
            'contacts' => [[
                'email' => $subscriber->getEmail(),
                'first_name' => $subscriber->getFirstName(),
                'last_name' => $subscriber->getLastName(),
            ]],
        ]));

        $response = $this->requestWithHeaders()->put($url, $contacts);
        dump('----');
        dump($response);
        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        if (in_array($response->status(), [202])) {
            $subscriberAccepted = SendgridResponderMail::for($response)->getAcceptedSubscriber($subscriber);
            sleep(1);
            $this->blabla($subscriberAccepted);
        }

        if (in_array($response->status(), [200, 201, 202])) {
            return SendgridResponderMail::for($response)->getVerifiedSubscriber($subscriber);
        }


        throw new CannotAddSubscriberException('Something went wrong');
    }

    public function blabla (SubscriberAccepted $subscriber)
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
    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified
    {
        $url = $this->endpoint . '/v3/marketing/contacts/search/emails';

        $data = json_decode('{"emails": ["'.$subscriber->getEmail().'"]}');

        $response = $this->requestWithHeaders()->post($url, $data);
        dump($response);
        if (in_array($response->status(), [404])) {
            throw new SubscriberNotFoundException("Subscriber Not Found");
        }

        if (in_array($response->status(), [200])) {
            return SendgridResponderMail::for($response)->getVerifiedSubscriber($subscriber);
        }

        throw new CannotGetSubscriberException("Something went wrong");
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
            return SendgridResponderMail::for($response)->getVerifiedSubscriberAfterAddToMailingList($subscriber, $mailingList);
        }

        if ($response->status() === 429) {
            throw new ProviderRateLimitException('Too many requests');
        }

        throw new CannotAddSubscriberToMailingListException('Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     *
     * @url https://docs.sendgrid.com/api-reference/lists/remove-contacts-from-a-list
     */
    public function deleteSubscriberFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {

        $url = $this->endpoint . '/v3/marketing/lists/' .$mailingList->getId() . '/contacts';

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromMailingListException('Subscriber not found in mailing list', ['subscriber' => $subscriber, 'mailingList' => $mailingList]);
        }

        if ($response->status() === 202) {
            SendgridResponderMail::for($response)->getVerifiedSubscriberAfterDeleteFromMailingList($subscriber, $mailingList);

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

