<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices\Slack;

use App\Interfaces\SubscriberManager\SubscriberServices\ChannelServices\ChannelDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\ChannelServices\BaseDeliveryService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeliveryService extends BaseDeliveryService implements ChannelDeliveryServiceInterface
{
    const TYPE = 'SLACK';
    protected string $type = self::TYPE;
    protected string $endpoint = 'https://slack.com/api';

    /**
     * @url https://api.slack.com/methods/auth.test
     */
    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/auth.test';

        $response = $this->requestWithHeaders()->post($url);
        dump($response->json());
        return $response->status() === 200;
    }

    /**
     * @url https://api.slack.com/methods/admin.users.invite
     */
    public function addToWorkspace()
    {

        //        $data = [
        //            'display_name' => 'John Doe',
        //            'email' => 'example@example.com',
        //            'first_name' => 'John',
        //            'last_name' => 'Doe',
        //        ];
    }

    /**
     * @url https://api.slack.com/methods/admin.users.remove
     */
    public function removeFromWorkspace()
    {

    }

    /**
     * @url https://api.slack.com/methods/admin.users.list
     */
    public function getWorkspaceUserList()
    {

    }

    /**
     * @url https://api.slack.com/methods/users.lookupByEmail
     */
    public function findUserByEmail()
    {

    }

    public function addSubscriber($subscriber) {
        $url = $this->endpoint . 'users.admin.invite';

        $data = [
            'email' => $subscriber->email->get(),
            'first_name' => $subscriber->firstName->get(),
            'last_name' => $subscriber->lastName->get(),
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        dump($response);
    }

    public function deleteSubscriber($subscriber) {
        $url = $this->endpoint . 'users.admin.invite';

        $data = [
            'email' => $subscriber->email->get(),
            'first_name' => $subscriber->firstName->get(),
            'last_name' => $subscriber->lastName->get(),
        ];

        $response = $this->requestWithHeaders()->post($url, $data);

        dump($response);
    }

    public function getType(): string
    {
        return $this->providerType;
    }

    /**
     * @url https://api.slack.com/web#slack-web-api__basics__post-bodies__url-encoded-bodies
     */
    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

