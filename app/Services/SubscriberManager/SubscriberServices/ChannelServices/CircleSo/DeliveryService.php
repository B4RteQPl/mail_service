<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices\CircleSo;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\ChannelServices\ChannelDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\ChannelServices\BaseDeliveryService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeliveryService extends BaseDeliveryService implements ChannelDeliveryServiceInterface
{
    const TYPE = 'CIRCLE_SO';
    protected string $type = self::TYPE;
    protected string $endpoint = 'https://app.circle.so/api/v1';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/me';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    public function getCommunityId(): string
    {
        $url = $this->endpoint . '/communities';

        $response = $this->requestWithHeaders()->get($url);

        return $response->status() === 200;
    }

    public function getSubscriberLists(): array
    {
        $url = $this->endpoint . '/space_groups?community_id={{community_id}}';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getChannelList();
    }

    public function verifySubscriber(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_group_members?email=' . $subscriber->email->get() . '&space_group_id=' . $subscriberList->id . '&community_id=' . $this->getCommunityId() . '';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->updateSubscriber($subscriber, $subscriberList);
    }

    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_group_members?email=' . $subscriber->email->get() . '&space_group_id=' . $subscriberList->id . '&community_id=' . $this->getCommunityId() . '';

        $response = $this->requestWithHeaders()->post($url);

        return Responder::for($response)->updateSubscriberAfterAddToSubscriberList($subscriber, $subscriberList);
    }

    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_group_members?email='.$subscriber->email->get().'&space_group_id=' .$subscriberList->id.'&community_id='.$this->getCommunityId().'';

        $response = $this->requestWithHeaders()->delete($url);

        return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
    }

    /**
     * @url https://api.slack.com/web#slack-web-api__basics__post-bodies__url-encoded-bodies
     */
    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Token ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

