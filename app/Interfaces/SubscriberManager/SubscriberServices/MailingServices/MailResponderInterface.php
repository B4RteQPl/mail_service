<?php

namespace App\Interfaces\SubscriberManager\SubscriberServices\MailingServices;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

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
