<?php

namespace App\Services\ExternalServices\ConvertKit\Client;

use App\Clients\BaseClient;
use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\ConvertKit\Data\ConvertKitDataSubscriber;
use App\Services\ExternalServices\ConvertKit\Data\ConvertKitDataTag;
use App\ValueObjects\Email;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as Log;

class ConvertKitClient extends BaseClient implements ConvertKitClientInterface
{
    protected string $apiSecret;
    protected string $endpoint;

    public function __construct(string $apiSecret)
    {
        $this->apiSecret = $apiSecret;
        $this->endpoint = 'https://api.convertkit.com/v3';
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): ?bool
    {
        try {
            $url = $this->endpoint . '/account' . $this->getAuthParams();

            $response = $this->request()->get($url);

            return $response->status() === 200;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ConvertKit', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function listTags(): ?array
    {
        try {
            $url = $this->endpoint . '/tags' . $this->getAuthParams();

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('ConvertKit', $response->json()['message'], $response->status());
            }

            return array_map(function($item) {
                $contact = new ConvertKitDataTag($item);
                return $contact->toArray();
            }, $response->json()['tags']);
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ConvertKit', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function listSubscribers(Email $email): array
    {
        try {
            $url = $this->endpoint . '/subscribers' . $this->getAuthParams() . '&email_address=' . $email->get();

            $response = $this->request()->get($url);

            if(!$response->successful()) {
                throw new ExternalServiceClientException('ConvertKit', $response->json()['message'], $response->status());
            }

            if ($response->json()['page'] < $response->json()['total_pages']) {
                Log::info('ConvertKitClient::listSubscribers() - more than one page of subscribers');
            }

            if ($response->json()['total_subscribers'] > 1) {
                Log::info('ConvertKitClient::listSubscribers() - more than one subscriber found');
            }

            if ($response->json()['total_subscribers'] === 0) {
                return [];
            }

            $subscriber = new ConvertKitDataSubscriber($response->json()['subscribers'][0]);
            return $subscriber->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ConvertKit', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function tagSubscriber(Email $email, string $tagId)
    {
        try {
            $url = $this->endpoint . '/tags/' . $tagId . '/subscribe' . $this->getAuthParams() . '&email=' . $email->get();

            $response = $this->request()->post($url, []);

            if(!$response->successful()) {
                throw new ExternalServiceClientException('ConvertKit', $response->json()['message'], $response->status());
            }

            $subscriber = new ConvertKitDataSubscriber($response->json()['subscription']['subscriber']);

            return $subscriber->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ConvertKit', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function removeTagFromSubscriber(string $subscriberId, string $tagId)
    {
        try {
            $url = $this->endpoint . '/subscribers/'. $subscriberId . '/tags/'. $tagId . $this->getAuthParams();

            $response = $this->request()->delete($url);

            if(!$response->successful()) {
                throw new ExternalServiceClientException('ConvertKit', $response->json()['message'], $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('ConvertKit', $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Api-Token' => $this->apiSecret,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    private function getAuthParams()
    {
        return '?api_secret=' . $this->apiSecret;
    }
}
