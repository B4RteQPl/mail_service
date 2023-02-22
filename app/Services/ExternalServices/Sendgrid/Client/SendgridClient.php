<?php

namespace App\Services\ExternalServices\Sendgrid\Client;

use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\BaseClient;
use App\Services\ExternalServices\Sendgrid\Data\SendgridDataContact;
use App\Services\ExternalServices\Sendgrid\Data\SendgridDataList;
use App\ValueObjects\Email;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SendgridClient extends BaseClient implements SendgridClientInterface
{
    protected string $endpoint = 'https://api.sendgrid.com';
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @url https://docs.sendgrid.com/api-reference/lists/get-all-lists
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): bool
    {
        try {
            $url = $this->endpoint . '/v3/marketing/lists';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                return false;
            }

            return $response->status() === 200;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://docs.sendgrid.com/api-reference/lists/get-all-lists
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getAllLists(): array
    {
        try {
            $url = $this->endpoint . '/v3/marketing/lists';

            $response = $this->request()->get($url);

            return array_map(function($item) {
                $group = new SendgridDataList($item);
                return $group->toArray();
            }, $response->json()['result']);
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @see https://docs.sendgrid.com/api-reference/contacts/add-or-update-a-contact
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function addContact(Email $email)
    {
        try {
            $url = $this->endpoint . '/v3/marketing/contacts';

            $contacts = json_decode(json_encode([
                'contacts' => [[
                    'email' => $email->get(),
                    // 'first_name' => $subscriber->firstName->get(),
                    // 'last_name' => $subscriber->lastName->get(),
               ]],
            ]));

            $response = $this->request()->put($url, $contacts);

            if(!$response->successful()) {
                throw new ExternalServiceClientException('Sendgrid', $response->json()['errors'][0]['message'], $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://docs.sendgrid.com/api-reference/contacts/get-contacts-by-emails
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getContactsByEmail(Email $email)
    {
        try {
            $url = $this->endpoint . '/v3/marketing/contacts/search/emails';

            $data = json_decode('{"emails": ["'.$email->get().'"]}');

            $response = $this->request()->post($url, $data);

            if(!$response->successful()) {
                return [];
            }
            $contact = new SendgridDataContact($response->json('result')[$email->get()]['contact']);
            return $contact->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://docs.sendgrid.com/api-reference/contacts/add-or-update-a-contact
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function addContactToList(Email $email, string $listId)
    {
        try {
            $url = $this->endpoint . '/v3/marketing/contacts';

            $data = json_decode('{"contacts": [{"email": "'.$email->get().'"}], "list_ids": ["'.$listId.'"]}');

            $response = $this->request()->put($url, $data);

            if(!$response->successful()) {
                throw new ExternalServiceClientException('Sendgrid', $response->json()['errors'][0]['message'], $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://docs.sendgrid.com/api-reference/lists/remove-contacts-from-a-list
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function removeContactFromList(string $contactId, string $listId)
    {
        try {
            $url = $this->endpoint . '/v3/marketing/lists/' .$listId . '/contacts?contact_ids=' . $contactId;

            $response = $this->request()->delete($url);

            return $response->json();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://docs.sendgrid.com/api-reference/contacts/delete-contacts
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function deleteContacts(string $contactId)
    {
        try {
            $url = $this->endpoint . '/v3/marketing/contacts' . '?ids=' . $contactId;

            $response = $this->request()->delete($url);

            return $response->json();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Sendgrid', $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}
