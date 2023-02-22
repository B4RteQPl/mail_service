<?php

namespace App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

interface MailResponderInterface
{
    /**
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array;
    public function updateSubscriber(SubscriberInterface $subscriber): SubscriberInterface;
    public function updateSubscriberAfterAddToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
    public function updateSubscriberAfterDeleteFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
}
