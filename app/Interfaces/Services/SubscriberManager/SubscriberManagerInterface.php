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
    public function getSubscriberList(): array;
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
}
