<?php

namespace App\Services\ExternalServices\MailerLite\Client;

use App\ValueObjects\Email;

interface MailerLiteClientInterface
{
    public function isConnectionOk(): bool;
    public function getListAllGroups(): array;
    public function createSubscriber(Email $email);
    public function fetchSubscriber(Email $email);
    public function assignSubscriberToGroup(string $subscriberId, string $groupId);
    public function unAssignSubscriberFromGroup(string $subscriberId, string $groupId);
    public function deleteSubscriber(string $subscriberId);
}
