<?php

namespace App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList;

use App\Exceptions\Services\SubscriberManager\SubscriberListNotSupportedException;

interface SubscriberListManagerInterface
{
    /**
     * @param SubscriberListInterface[] $lists
     * @throws SubscriberListNotSupportedException
     */
    public function __construct(array $lists = []);

    /**
     * @param SubscriberListInterface[] $lists
     * @throws SubscriberListNotSupportedException
     */
    public function set(array $lists): void;
    public function get(): array;
    public function add(SubscriberListInterface $listToAdd): void;
    public function delete(SubscriberListInterface $listToDelete): void;
    public function has(SubscriberListInterface $listToVerify): bool;
    public function isEmpty(): bool;

    public function toArray(): array;
}
