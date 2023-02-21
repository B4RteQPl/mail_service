<?php

namespace App\Services\ExternalServices\ActiveCampaign\Client;

use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\ActiveCampaign\Data\ActiveCampaignDataContact;
use App\Services\ExternalServices\ActiveCampaign\Data\ActiveCampaignDataContactList;
use App\Services\ExternalServices\ActiveCampaign\Data\ActiveCampaignDataList;
use App\Services\ExternalServices\BaseClient;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ActiveCampaignClient extends BaseClient implements ActiveCampaignClientInterface
{
    const LIST_STATUS_SUBSCRIBED = 1;
    const LIST_STATUS_UNSUBSCRIBED = 2;

    protected string $authKey;
    protected string $endpoint;

    public function __construct(string $authKey, string $endpoint)
    {
        $this->authKey = $authKey;
        $this->endpoint = $endpoint . '/api/3';
    }

    /**
     * @url https://developers.activecampaign.com/reference/retrieve-all-lists
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): bool
    {
        try {
            $url = $this->endpoint . '/lists';

            $response = $this->request()->get($url);

            return $response->status() === 200;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ActiveCampaign', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.activecampaign.com/reference/retrieve-all-lists
     *
     * @return ?ActiveCampaignDataList[]
     * @throws ExternalServiceClientException
     */
    public function retrieveAllLists(): ?array
    {
        try {
            $url = $this->endpoint . '/lists';

            $response = $this->request()->get($url);

            if ($response->successful()) {
                return array_map(function($item) {
                    $list = new ActiveCampaignDataList($item);
                    return $list->toArray();
                }, $response->json()['lists']);
            } else {
                throw new ExternalServiceClientException('ActiveCampaign', $response->json()['message'], $response->status());
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ActiveCampaign', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.activecampaign.com/reference/create-a-new-contact
     *
     * @return ?ActiveCampaignDataContact[]
     * @throws ExternalServiceClientException
     */
    public function createNewContact(Email $email, FirstName $firstName, LastName $lastName): ?array
    {
        try {
            $url = $this->endpoint . '/contacts';

            $contact = array(
                'contact' => [
                    'email' => $email->get(),
                    'firstName' => $firstName->get(),
                    'lastName' => $lastName->get(),
                ]
            );

            $response = $this->request()->post($url, $contact);

            if ($response->successful()) {
                return array_map(function($item) {
                    $list = new ActiveCampaignDataContact($item);
                    return $list->toArray();
                }, $response->json())['contact'];
            } else {
                throw new ExternalServiceClientException('ActiveCampaign', $response->json()['message'], $response->status());
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ActiveCampaign', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.activecampaign.com/reference/list-all-contacts
     *
     * @return ?ActiveCampaignDataContact
     * @throws ExternalServiceClientException
     */
    public function searchContactByEmail(Email $email)
    {
        try {
            $url = $this->endpoint . '/contacts?email=' . $email->get();

            $response = $this->request()->get($url);

            if ($response->successful()) {
                $contact = array_map(function($item) {
                    $list = new ActiveCampaignDataContact($item);
                    return $list->toArray();
                }, $response->json()['contacts']);

                return $contact[0] ?? [];
            } else {
                throw new ExternalServiceClientException('ActiveCampaign', $response->json()['message'], $response->status());
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ActiveCampaign', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.activecampaign.com/reference/update-list-status-for-contact
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function updateListStatusForContact(string $listId, string $contactId, string $status): array
    {
        try {
            $this->assertStatus($status);

            $url = $this->endpoint . '/contactLists';

            $contactList = array(
                'contactList' => [
                    'list' => $listId,
                    'contact' => $contactId,
                    'status' => $status,
                ]
            );

            $response = $this->request()->post($url, $contactList);
            if ($response->successful()) {
                $contact = new ActiveCampaignDataContact($response->json('contacts')[0]);
                $contactList = new ActiveCampaignDataContactList($response->json('contactList'));
                return [
                    'contact' => $contact->toArray(),
                    'contactList' => $contactList->toArray(),
                ];
            } else {
                throw new ExternalServiceClientException('ActiveCampaign', $response->json()['message'], $response->status());
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ActiveCampaign', $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Api-Token' => $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    private function assertStatus(string $status)
    {
        if (!in_array($status, [self::LIST_STATUS_SUBSCRIBED, self::LIST_STATUS_UNSUBSCRIBED])) {
            throw new \InvalidArgumentException('Invalid status');
        }
    }
}
