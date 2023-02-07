<?php

namespace App\Service\SubscriberService\MailProviders\GetResponses;

use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Interfaces\GroupProviderInterface;
use App\Service\SubscriberService\MailProviders\BaseMailProvider;
use App\Service\SubscriberService\Subscriber;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GetResponseMailProvider extends BaseMailProvider implements GroupProviderInterface
{
    protected string $endpoint = 'https://api.getresponse.com/v3';
    protected string $apiKey;
    protected string $testGroupId = 'TEST_GETRESPONSE_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_GETRESPONSE_SECOND_GROUP_ID';

    public function __construct(string $apiKey, string $apiUrl = null)
    {
        $this->apiKey = $apiKey;
    }

    public function addSubscriber(string $email)
    {

    }

    /**
     * @throws CannotAddSubscriberToMailingListException
     */
    public function addSubscriberToGroup(Subscriber $subscriber): array
    {
        $url = $this->endpoint . '/contacts';

        $data = [
            'email' => $subscriber->getEmail(),
            'name' => $subscriber->getFirstName(),
            'campaign' => [
                'campaignId' => $subscriber->getGroupId(),
            ],
        ];

        $response = $this->requestWithHeaders()->post($url, $data);
//        dump($response);
//        dump($response->status());
//        dump($response->json());

        if (in_array($response->status(), [200, 201, 202])) {
            return SubscriberResponse::convert($response, $subscriber);
        }
        if (in_array($response->status(), [202])) {
            // todo here is wrong... 202 accepted?? what should we do??
            return SubscriberResponse::getDTOArray($subscriber);
        }

        throw new CannotAddSubscriberToMailingListException('Something went wrong');
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     */
    public function deleteSubscriberFromGroup(Subscriber $subscriber): bool
    {
        $url = $this->endpoint . '/campaigns/'. $subscriber->getGroupId(). '/contacts/'. $subscriber->getEmail();

        $response = $this->requestWithHeaders()->delete($url);

        if ($response->status() === 204) {
            return true;
        }

        if ($response->status() === 404) {
            throw new CannotDeleteSubscriberFromMailingListException('Subscriber not found in mailing group');
        }

        throw new CannotDeleteSubscriberFromMailingListException('Something went wrong');
    }

    public function getSubscriberByEmail(string $email): array
    {
        $url = $this->endpoint . '/subscribers/' . $email;

        $response = $this->requestWithHeaders()->get($url);

        if (in_array($response->status(), [200])) {
            return SubscriberResponse::convert($response);
        }

        return [];
    }

    public function isSubscriberAssignedToGroup(string $email, string $groupId): bool
    {
        $url = $this->endpoint . '/subscribers/'. $email;

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

    public function getSubscriberGroups(): array
    {
        $url = $this->endpoint . '/campaigns';

        $response = $this->requestWithHeaders()->get($url);

        return GroupResponse::convert($response->json());
    }

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/accounts';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'X-Auth-Token' => 'api-key ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

