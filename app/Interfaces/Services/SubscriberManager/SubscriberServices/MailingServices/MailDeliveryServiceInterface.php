<?php

namespace App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

interface MailDeliveryServiceInterface
{
    public function __construct(string $apiKey, ?string $apiUrl);

    public function isConnectionOk(): bool;

    /**
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array;

    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface;
    public function verifySubscriber(SubscriberInterface $subscriber, ?SubscriberListInterface $subscriberList): SubscriberInterface;
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface;

    public function getType(): string;

    // TODO: find better approach to get testing data for each provider
    public function getTestingGroupId(): string;
    public function getTestingSecondGroupId(): string;
}
