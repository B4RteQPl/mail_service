<?php

namespace App\Interfaces\Services\SubscriberManager;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\ServiceInterface;

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
