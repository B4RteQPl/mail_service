<?php

namespace App\Services\ExternalServices\GetResponse\Client;

use App\Clients\BaseClient;
use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\GetResponse\Data\GetResponseDataCampaign;
use App\Services\ExternalServices\GetResponse\Data\GetResponseDataCampaignContact;
use App\ValueObjects\Email;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GetResponseClient extends BaseClient implements GetResponseClientInterface
{
    protected string $endpoint = 'https://api.getresponse.com/v3';
    protected string $authKey;

    public function __construct(string $authKey)
    {
        $this->authKey = $authKey;
    }

    /**
     * @url https://apireference.getresponse.com/#operation/getAccount
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): bool
    {
        try {
            $url = $this->endpoint . '/accounts';

            $response = $this->request()->get($url);

            return $response->status() === 200;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('GetResponse', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://apireference.getresponse.com/#operation/getCampaignList
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getCampaignList()
    {
        try {
            $url = $this->endpoint . '/campaigns';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('GetResponse', $response->json()['message'], $response->status());
            }

            return array_map(function ($item) {
                $contact = new GetResponseDataCampaign($item);
                return $contact->toArray();
            }, $response->json());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('GetResponse', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://apireference.getresponse.com/#operation/getContactsFromCampaign
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getContactsFromCampaign(Email $email, string $campaignId)
    {
        dump('getContactsFromCampaign');
        try {
            $url = $this->endpoint . '/campaigns/'.$campaignId.'/contacts?query[email]=' . $email->get();

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('GetResponse', $response->json()['message'], $response->status());
            }

            // if array is empty
            if (empty($response->json())) {
                return [];
            }

            $campaign = new GetResponseDataCampaign($response->json()[0]['campaign']);
            $contact = new GetResponseDataCampaignContact($response->json()[0]);

            return [
                'campaign' => $campaign->toArray(),
                'contact' => $contact->toArray(),
            ];
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('GetResponse', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://apireference.getresponse.com/#operation/createContact
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function createContact(Email $email, string $campaignId)
    {
        try {
            $url = $this->endpoint . '/contacts';

            $data = [
                'email' => $email->get(),
                'campaign' => [
                    'campaignId' => $campaignId,
                ],
            ];

            $response = $this->request()->post($url, $data);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('GetResponse', $response->json()['message'], $response->status());
            }

            if (!$response->successful()) {
                throw new ExternalServiceClientException('GetResponse', $response->json()['message'], $response->status());
            }

            return $response->status() === 202;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('GetResponse', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://apireference.getresponse.com/#operation/deleteContact
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function deleteContact(string $contactId): bool
    {
        dump('deleteContact');
        try {
            $url = $this->endpoint . '/contacts/' . $contactId;

            $response = $this->request()->delete($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('GetResponse', $response->json()['message'], $response->status());
            }
            if ($response->status() === 204) {
                return true;
            }

            throw new ExternalServiceClientException('GetResponse', 'Contact not deleted', $response->status());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('GetResponse', $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'X-Auth-Token' => 'api-key ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

}
