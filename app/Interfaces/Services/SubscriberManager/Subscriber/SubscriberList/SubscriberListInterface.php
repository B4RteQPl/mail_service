<?php

namespace App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList;

interface SubscriberListInterface
{
    public function __construct(string $id, string $name, string $type);

    public function setId(string $id): void;
    public function setName(string $name): void;
    public function setType(string $type): void;
    public function hasType(string $type): bool;

    public function setStatusNotVerified(): void;
    public function setStatusVerified(): void;
    public function setStatusVerificationPending(): void;

    public function isStatusVerificationPending(): bool;
    public function isStatusVerified(): bool;
    public function isStatusNotVerified(): bool;

    public function toArray(): array;

    public static function isInvalid(SubscriberListInterface $list): bool;

    /**
     * @param SubscriberListInterface[] $lists
     * @return bool
     */
    public static function isInvalidArray(array $lists): bool;
}
