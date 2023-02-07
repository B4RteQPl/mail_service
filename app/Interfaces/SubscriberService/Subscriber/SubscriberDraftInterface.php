<?php

namespace App\Interfaces\SubscriberService\Subscriber;

use App\Service\SubscriberService\Subscriber\SubscriberVerified;

interface SubscriberDraftInterface
{
    public function __construct(string $email, string $firstName, string $lastName, array $mailingLists);
    public function getSubscriberVerified(string $id): SubscriberVerified;

    public function isVerified(): bool;
    public function isDraft(): bool;
    public function isAccepted(): bool;

    public function toArray(): array;
}
