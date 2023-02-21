<?php

namespace App\Services\ExternalServices\MailerLite\Client;

use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\BaseClient;
use App\Services\ExternalServices\MailerLite\Data\MailerLiteDataGroup;
use App\Services\ExternalServices\MailerLite\Data\MailerLiteDataSubscriber;
use App\ValueObjects\Email;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MailerLiteClient extends BaseClient implements MailerLiteClientInterface
{
    protected string $endpoint = 'https://connect.mailerlite.com/api';
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @url https://developers.mailerlite.com/docs/groups.html#list-all-groups
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): bool
    {
        try {
            $url = $this->endpoint . '/groups';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                return false;
            }

            return $response->status() === 200;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLite', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.mailerlite.com/docs/groups.html#list-all-groups
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getListAllGroups(): array
    {
        try {
            $url = $this->endpoint . '/groups';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLite', $response->json()['message'], $response->status());
            }

            return array_map(function($item) {
                $group = new MailerLiteDataGroup($item);
                return $group->toArray();
            }, $response->json()['data']);
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLite', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.mailerlite.com/docs/subscribers.html#create-upsert-subscriber
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function createSubscriber(Email $email)
    {
        try {
            $url = $this->endpoint . '/subscribers';

            $data = [
                'email' => $email->get(),
            ];

            $response = $this->request()->post($url, $data);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLite', $response->json()['message'], $response->status());
            }

            $subscriber = new MailerLiteDataSubscriber($response->json('data'));
            return $subscriber->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLite', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.mailerlite.com/docs/subscribers.html#fetch-a-subscriber
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function fetchSubscriber(Email $email)
    {
        try {
            $url = $this->endpoint . '/subscribers/' . $email->get();

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLite', $response->json()['message'], $response->status());
            }

            $subscriber = new MailerLiteDataSubscriber($response->json('data'));
            return $subscriber->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLite', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.mailerlite.com/docs/groups.html#assign-subscriber-to-a-group
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function assignSubscriberToGroup(string $subscriberId, string $groupId)
    {
        try {
            $url = $this->endpoint . '/subscribers/' . $subscriberId . '/groups/' . $groupId;
            $response = $this->request()->post($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLite', $response->json()['message'], $response->status());
            }

            $group = new MailerLiteDataGroup($response->json('data'));
            return $group->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLite', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://developers.mailerlite.com/docs/groups.html#unassign-subscriber-from-a-group
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function unAssignSubscriberFromGroup(string $subscriberId, string $groupId)
    {
        try {
            $url = $this->endpoint . '/subscribers/'. $subscriberId . '/groups/'. $groupId;

            $response = $this->request()->delete($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('MailerLite', $response->json()['message'], $response->status());
            }

            return $response->status() === 204;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('MailerLite', $e->getMessage(), $e->getCode(), $e);
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
