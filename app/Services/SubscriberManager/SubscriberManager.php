<?php

namespace App\Services\SubscriberManager;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberManagerInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\ServiceInterface;

class SubscriberManager implements SubscriberManagerInterface
{

    protected ServiceInterface $service;

    public function __construct(ServiceInterface $service) {
        $this->service = $service;
    }

    public function isConnectionOk(): bool
    {
        return $this->service->isConnectionOk();
    }

    /**
     * @return SubscriberListInterface[]
     */
    public function getLists(): array
    {
        return $this->service->getLists();
    }

    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        return $this->service->addSubscriberToSubscriberList($subscriber, $subscriberList);
    }

    public function deleteSubscriberFromList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        return $this->service->deleteSubscriberFromList($subscriber, $subscriberList);
    }
}
