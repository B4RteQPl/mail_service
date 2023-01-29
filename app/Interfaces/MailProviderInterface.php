<?php

namespace App\Interfaces;

interface MailProviderInterface
{
    public function __construct(string $apiKey);

    public function addSubscriberToGroup(string $email, string $name, string $groupId);
    public function deleteSubscriberFromGroup(string $email, string $groupId);
    public function getGroups(): array;
    public function getSubscriber(string $email): array;
    public function isConnectionOk(): bool;
}
