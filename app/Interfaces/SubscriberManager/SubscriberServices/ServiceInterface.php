<?php

namespace App\Interfaces\SubscriberManager\SubscriberServices;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

interface ServiceInterface
{

    public function isConnectionOk(): bool;

    /**
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array;
    public function verifySubscriber(SubscriberInterface $subscriber, ?SubscriberListInterface $subscriberList): SubscriberInterface;
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
}
