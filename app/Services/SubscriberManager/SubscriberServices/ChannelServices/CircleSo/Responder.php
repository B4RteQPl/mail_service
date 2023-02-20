<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices\CircleSo;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\ChannelList;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\CommunityList;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\CommunitySpaceList;

class Responder
{

    protected $response;

    private function __construct($response)
    {
        dump($response);
        $this->response = $response;
    }

    public static function for($response): self
    {
        return new self($response->json());
    }

    /**
     * @return SubscriberListInterface[]
     */
    public function getCommunityList(): array
    {
        $communities = [];

        foreach ($this->response as $community) {
            $community = new CommunityList($community['id'], $community['name'], DeliveryService::TYPE);
            $community->setStatusVerified();
            $communities[] = $community;
        }

        return $communities;
    }

    /**
     * @return ChannelList[]
     */
    public function getCommunitySpaceList(SubscriberListInterface $communityList): array
    {
        $spaces = [];

        foreach ($this->response as $space) {
            $space = new CommunitySpaceList($space['id'], $space['name'], DeliveryService::TYPE);
            $space->setStatusVerified();
            $space->forList($communityList);

            $spaces[] = $space;
        }

        return $spaces;
    }

    public function updateSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $id = $this->response['contact']['id'];

        $subscriber->setStatusVerified($id);

        return $subscriber;
    }

    public function updateSubscriberAfterAddToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $id = $this->response['subscription']['subscriber']['id'];

        $subscriber->setStatusVerified($id);
        $subscriberList->setStatusVerified();
        $subscriber->channelLists()->add($subscriberList);

        return $subscriber;
    }

    public function updateSubscriberAfterDeleteFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $subscriber->channelLists()->delete($subscriberList);

        return $subscriber;
    }

}
