<?php

namespace App\Service\SubscriberService\ChatProviders\Slack;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SlackProvider
{
    const PROVIDER_TYPE = 'SLACK';
    protected string $providerType = self::PROVIDER_TYPE;
    protected string $endpoint = 'https://slack.com/api';

    public function __construct(string $authKey, string $apiUrl = null)
    {
        $this->authKey = $authKey;
        if (!empty($apiUrl)) {
            $this->apiUrl = $apiUrl;
        }
    }

    public function addSubscriber($subscriber) {
        $url = $this->endpoint . 'users.admin.invite';

        $data = [
            'email' => $subscriber->getEmail(),
            'first_name' => $subscriber->getFirstName(),
            'last_name' => $subscriber->getLastName(),
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        dump($response);
    }

    public function deleteSubscriber($subscriber) {
        $url = $this->endpoint . 'users.admin.invite';

        $data = [
            'email' => $subscriber->getEmail(),
            'first_name' => $subscriber->getFirstName(),
            'last_name' => $subscriber->getLastName(),
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        dump($response);
    }

    public function getProviderType(): string
    {
        return $this->providerType;
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

