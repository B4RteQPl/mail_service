<?php

namespace App\Service\MailService\MailProviders\MailerLiteClassic;

use App\Exceptions\Service\MailService\CannotAddSubscriberToGroupException;
use App\Exceptions\Service\MailService\CannotDeleteSubscriberFromGroupException;
use App\Interfaces\MailProviderInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MailerLiteClassicMailProvider implements MailProviderInterface
{
    const ENDPOINT = 'https://connect.mailerlite.com/api';

    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws CannotAddSubscriberToGroupException
     */
    public function addSubscriberToGroup(string $email, string $name, string $groupId): array
    {
        $url = self::ENDPOINT . '/subscribers';

        $data = [
            'email' => $email,
            'name' => $name,
            'groups' => [$groupId],
            'type' => 'active',
            'resubscribe' => true,
            'fields' => [],
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        if ($response->status() === 429) {
            throw new CannotAddSubscriberToGroupException('Too many requests');
        }

        if (in_array($response->status(), [200, 201])) {
            $subscriber = $response->json()['data'];

            // check if some subscriber groups contains $groupId and throw exception if not
            $groupsIDs = array_column($subscriber['groups'], 'id');
            if (!in_array($groupId, $groupsIDs)) {
                throw new CannotAddSubscriberToGroupException('Mailing group not exist');
            }

            return MailerLiteClassicSubscriberDTO::toArray($subscriber);
        }

        throw new CannotAddSubscriberToGroupException('Something went wrong');
    }

    public function getSubscriber(string $email): array
    {
        $url = self::ENDPOINT . '/subscribers/' . $email;

        $response = $this->requestWithHeaders()->get($url);

        if ($response->status() === 200) {
            return MailerLiteClassicSubscriberDTO::toArray($response->json()['data']);
        }

        return [];
    }

    public function isSubscriberAssignedToGroup(string $email, string $groupId): bool
    {
        $url = self::ENDPOINT . '/subscribers/'. $email;

        $response = $this->requestWithHeaders()->get($url);

        if ($response->status() === 200) {
            // when group key not exists
            if (!array_key_exists('groups', $response->json())) {
                return false;
            }

            // check if subscriber is assigned to group
            $groups = $response->json()['groups'];
            foreach ($groups as $group) {
                if ($group['id'] === $groupId) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @throws CannotDeleteSubscriberFromGroupException
     */
    public function deleteSubscriberFromGroup(string $email, string $groupId): bool
    {
        $url = self::ENDPOINT . '/subscribers/'. $email . '/groups/'. $groupId;

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 429) {
            throw new CannotDeleteSubscriberFromGroupException('Too many requests');
        }

        if ($response->status() === 204) {
            return true;
        }

        if ($response->status() === 404) {
            // todo to clarify what is expected result
            return true;
            // throw new CannotDeleteSubscriberFromGroupException('Subscriber not found in mailing group');
        }

        throw new CannotDeleteSubscriberFromGroupException('Something went wrong');
    }

    public function getGroups(): array
    {
        $url = self::ENDPOINT . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return MailerLiteClassicGroupDTO::toArray($response->json());
    }

    public function isConnectionOk(): bool
    {
        $url = self::ENDPOINT . '/groups';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'X-MailerLite-ApiKey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

