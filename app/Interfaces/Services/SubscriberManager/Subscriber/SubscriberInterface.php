<?php

namespace App\Interfaces\Services\SubscriberManager\Subscriber;

use App\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManager;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

interface SubscriberInterface
{
    public function __construct(Email $email, ?FirstName $firstName, ?LastName $lastName, array $mailingLists = [], array $channelLists = []);
    public function email(): Email;
    public function firstName(): FirstName;
    public function lastName(): LastName;
    public function mailingLists(): SubscriberListManager;
    public function channelLists(): SubscriberListManager;

    public function setStatusNotVerified(): void;
    public function setStatusVerified(string $id): void;
    public function setStatusVerificationPending(?array $job = []): void;

    public function isStatusNotVerified(): bool;
    public function isStatusVerified(): bool;
    public function isStatusVerificationPending(): bool;

    public function toArray(): array;
}
