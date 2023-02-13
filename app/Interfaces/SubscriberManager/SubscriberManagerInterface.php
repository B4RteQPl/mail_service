<?php

namespace App\Interfaces\SubscriberManager;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\ServiceInterface;

interface SubscriberManagerInterface
{
    public function __construct(ServiceInterface $service);
    public function isConnectionOk(): bool;

    /**
     * @return SubscriberListInterface[]
     */
    public function getLists(): array;
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
    public function deleteSubscriberFromList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
}
