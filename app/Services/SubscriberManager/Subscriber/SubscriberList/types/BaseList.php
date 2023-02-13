<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList\types;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

class BaseList implements SubscriberListInterface
{

    const STATUS_NOT_VERIFIED = 'NOT_VERIFIED';
    const STATUS_VERIFIED = 'VERIFIED';
    const STATUS_VERIFICATION_PENDING = 'VERIFICATION_PENDING';

    protected string $id;
    protected string $name;
    protected string $type;
    protected ?string $status = self::STATUS_NOT_VERIFIED;

    public function __construct(string $id, string $name, string $type)
    {
        $this->assertNotEmpty($id, 'id');
        $this->assertNotEmpty($name, 'name');
        $this->assertNotEmpty($type, 'type');

        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    public function __get($name)
    {
        if ($name === 'id') {
            return $this->id;
        }
        if ($name === 'name') {
            return $this->name;
        }
        if ($name === 'type') {
            return $this->type;
        }
    }

    public function setId(string $id): void
    {
        $this->assertNotEmpty($id, 'id');

        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->assertNotEmpty($name, 'name');

        $this->name = $name;
    }

    public function setType(string $type): void
    {
        $this->assertNotEmpty($type, 'type');

        $this->type = $type;
    }

    public function hasType(string $type): bool
    {
        return $this->type === $type;
    }

    public function setStatusNotVerified(): void
    {
        $this->status = self::STATUS_NOT_VERIFIED;
    }

    public function setStatusVerified(): void
    {
        $this->status = self::STATUS_VERIFIED;
    }

    public function setStatusVerificationPending(): void
    {
        $this->status = self::STATUS_VERIFICATION_PENDING;
    }

    public function isStatusVerificationPending(): bool
    {
        return $this->status === self::STATUS_VERIFICATION_PENDING;
    }

    public function isStatusVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isStatusNotVerified(): bool
    {
        return $this->status === self::STATUS_NOT_VERIFIED;
    }


    private function assertNotEmpty(string $value, string $name): void
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('List '.$name.' cannot be empty');
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
        ];
    }

    // placeholders, because are overwritten in MailingList and ChannelList
    public static function isInvalid(SubscriberListInterface $list): bool
    {
        return true;
    }

    public static function isInvalidArray(array $lists): bool
    {
        return true;
    }
}
