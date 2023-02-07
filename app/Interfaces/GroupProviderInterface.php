<?php

namespace App\Interfaces;

use App\Service\SubscriberService\Subscriber;

interface GroupProviderInterface
{
    public function __construct(string $apiKey, ?string $apiUrl);

    public function addSubscriberToMailingList(Subscriber $subscriber): array;
    public function deleteSubscriberFromGroup(Subscriber $subscriber): bool;
    public function getSubscriberGroups(): array;
    public function getSubscriberByEmail(Subscriber $subscriber): array;
    public function isConnectionOk(): bool;

    // TODO: find better approach to get testing data for each provider
    public function getTestingGroupId(): string;
    public function getTestingSecondGroupId(): string;
}
