<?php

namespace App\Interfaces\SubscriberService\Subscriber;

interface SubscriberVerifiedInterface
{
    public function __construct(string $id, string $email, string $firstName, string $lastName, array $mailingLists);

    public function getId();
    public function setId(string $id): void;

    public function isVerified(): bool;
    public function isDraft(): bool;
    public function isAccepted(): bool;

    public function toArray(): array;
}
