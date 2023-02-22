<?php

namespace App\Services\ExternalServices\MailerLiteClassic\Client;

use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\BaseClient;
use App\Services\ExternalServices\MailerLiteClassic\Data\MailerLiteClassicDataGroup;
use App\Services\ExternalServices\MailerLiteClassic\Data\MailerLiteClassicDataSubscriber;
use App\ValueObjects\Email;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MailerLiteClassicClient extends BaseClient implements MailerLiteClassicClientInterface
{
    protected string $endpoint = 'https://api.mailerlite.com/api/v2';
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): bool
    {
        try {
            $url = $this->endpoint . '/me';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                return false;
            }

            return $response->status() === 200;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers-classic.mailerlite.com/reference/groups
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getListAllGroups(): array
    {
        try {
            $url = $this->endpoint . '/groups';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLiteClassic', $response->json()['message'], $response->status());
            }

            return array_map(function($item) {
                $group = new MailerLiteClassicDataGroup($item);
                return $group->toArray();
            }, $response->json());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers-classic.mailerlite.com/reference/create-a-subscriber
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function createSubscriber(Email $email)
    {
        try {
            $url = $this->endpoint . '/subscribers';

            $data = [
                'email' => $email->get(),
                //                'fields' => [
                //                    'first_name' => $firstName->get(),
                //                    'last_name' => $lastName->get(),
                //                ]
            ];

            $response = $this->request()->post($url, $data);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLiteClassic', $response->json()['message'], $response->status());
            }
            $subscriber = new MailerLiteClassicDataSubscriber($response->json());
            return $subscriber->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers-classic.mailerlite.com/reference/single-subscriber
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function fetchSubscriber(Email $email)
    {
        try {
            $url = $this->endpoint . '/subscribers/' . $email->get();

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLiteClassic', $response->json()['message'], $response->status());
            }

            $subscriber = new MailerLiteClassicDataSubscriber($response->json());
            return $subscriber->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers-classic.mailerlite.com/reference/groupsby_group_namesubscriberssubscriber_idassign
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function assignSubscriberToGroup(string $subscriberId, string $groupName)
    {
        try {
            $url = $this->endpoint . '/groups/group_name/subscribers/' . $subscriberId . '/assign';
            $data = ['group_name' => $groupName];

            $response = $this->request()->post($url, $data);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLiteClassic', $response->json()['message'], $response->status());
            }

            $group = new MailerLiteClassicDataGroup($response->json());
            return $group->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers-classic.mailerlite.com/reference/remove-subscriber
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function unAssignSubscriberFromGroup(string $subscriberId, string $groupId)
    {
        try {
            $url = $this->endpoint . '/groups/'. $groupId . '/subscribers/'. $subscriberId;

            $response = $this->request()->delete($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLiteClassic', $response->json()['message'], $response->status());
            }

            return $response->status() === 204;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function deleteSubscriber(string $subscriberId)
    {
        try {
            $url = $this->endpoint . '/subscribers/'. $subscriberId;

            $response = $this->request()->delete($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLiteClassic', $response->json()['message'], $response->status());
            }

            return $response->status() === 204;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLiteClassic', $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'X-MailerLite-ApiKey' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}
