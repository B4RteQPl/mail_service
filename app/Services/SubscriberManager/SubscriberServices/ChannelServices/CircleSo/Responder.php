<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices\CircleSo;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\ChannelList;

class Responder
{

    protected $response;

    private function __construct($response)
    {
        $this->response = $response;
    }

    public static function for($response): self
    {
        return new self($response->json());
    }

    /**
     * @return ChannelList[]
     */
    public function getChannelList(): array
    {
        $channels = [];

        foreach ($this->response['lists'] as $group) {
            $channel = new ChannelList($group['id'], $group['name'], DeliveryService::TYPE);

            $channels[] = $channel;
        }

        return $channels;
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
