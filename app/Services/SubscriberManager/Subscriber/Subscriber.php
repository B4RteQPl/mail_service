<?php

namespace App\Services\SubscriberManager\Subscriber;

use App\Exceptions\Service\SubscriberService\SubscriberListNotSupportedException;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManager;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class Subscriber implements SubscriberInterface
{

    const STATUS_NOT_VERIFIED = 'NOT_VERIFIED';
    const STATUS_VERIFIED = 'VERIFIED';
    const STATUS_VERIFICATION_PENDING = 'VERIFICATION_PENDING';

    protected Email $email;
    protected ?FirstName $firstName;
    protected ?LastName $lastName;
    protected ?SubscriberListManager $mailingLists;
    protected ?SubscriberListManager $channelLists;

    protected ?string $status = self::STATUS_NOT_VERIFIED;
    protected ?string $id = null;
    protected ?array $job = null;

    /**
     * @throws SubscriberListNotSupportedException
     */
    public function __construct(Email $email, ?FirstName $firstName = null, ?LastName $lastName = null, array $mailingLists = [], array $channelLists = [])
    {
        $this->email = $email;

        $this->firstName = empty($firstName) ? new FirstName('') : $firstName;
        $this->lastName = empty($lastName) ? new LastName('') : $lastName;

        $this->mailingLists = new SubscriberListManager($mailingLists);
        $this->channelLists = new SubscriberListManager($channelLists);
    }

    public function __get($name) {
        if ($name === 'email') {
            return $this->email;
        }
        if ($name === 'firstName') {
            return $this->firstName;
        }
        if ($name === 'lastName') {
            return $this->lastName;
        }
        if ($name === 'mailingLists') {
            return $this->mailingLists;
        }
        if ($name === 'channelLists') {
            return $this->channelLists;
        }
        if ($name === 'status') {
            return $this->status;
        }
        if ($name === 'id') {
            return $this->id;
        }
        if ($name === 'job') {
            return $this->job;
        }
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function mailingLists(): SubscriberListManager
    {
        return $this->mailingLists;
    }

    public function channelLists(): SubscriberListManager
    {
        return $this->channelLists;
    }

    public function setStatusNotVerified(): void
    {
        $this->id = null;
        $this->status = self::STATUS_NOT_VERIFIED;
    }

    public function setStatusVerified(string $id): void
    {
        $this->id = $id;
        $this->status = self::STATUS_VERIFIED;
    }

    public function setStatusVerificationPending(?array $job = []): void
    {
        // todo in this place add to verification queue
        $this->job = $job;
        $this->status = self::STATUS_VERIFICATION_PENDING;
    }

    public function isStatusNotVerified(): bool
    {
        return $this->status === self::STATUS_NOT_VERIFIED;
    }

    public function isStatusVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isStatusVerificationPending(): bool
    {
        return $this->status === self::STATUS_VERIFICATION_PENDING;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email->get(),
            'firstName' => $this->firstName->get(),
            'lastName' => $this->lastName->get(),
            'mailingLists' => $this->mailingLists->toArray(),
            'channelLists' => $this->channelLists->toArray(),
            'status' => $this->status,
            'id' => $this->id,
            'job' => $this->job,
        ];
    }
}
