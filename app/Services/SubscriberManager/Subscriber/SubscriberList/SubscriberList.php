<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

class SubscriberList
{
    private SubscriberListInterface $list;

    public function __construct(SubscriberListInterface $list) {
        $this->list = $list;
    }

    public function __get($name)
    {
        if ($name === 'id') {
            return $this->list->id;
        }
        if ($name === 'name') {
            return $this->list->name;
        }
        if ($name === 'type') {
            return $this->list->type;
        }
        if ($name === 'status') {
            return $this->list->status;
        }
    }

    public function setId(string $id): void
    {
        $this->list->setId($id);
    }

    public function setName(string $name): void
    {
        $this->list->setName($name);
    }

    public function setType(string $type): void
    {
        $this->list->setType($type);
    }

    public function setStatusNotVerified(): void
    {
        $this->list->setStatusNotVerified();
    }

    public function setStatusVerified(): void
    {
        $this->list->setStatusVerified();
    }

    public function setStatusVerificationPending(): void
    {
        $this->list->setStatusVerificationPending();
    }

    public function isStatusVerificationPending(): bool
    {
        return $this->list->isStatusVerificationPending();
    }

    public function isStatusVerified(): bool
    {
        return $this->list->isStatusVerified();
    }

    public function isStatusNotVerified(): bool
    {
        return $this->list->isStatusNotVerified();
    }

    public function hasType(string $type): bool
    {
        return $this->list->hasType($type);
    }

    public function toArray(): array
    {
        return $this->list->toArray();
    }
}
