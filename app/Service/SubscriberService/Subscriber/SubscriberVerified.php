<?php

namespace App\Service\SubscriberService\Subscriber;

use App\Interfaces\SubscriberService\Subscriber\SubscriberVerifiedInterface;

final class SubscriberVerified extends SubscriberBase implements SubscriberVerifiedInterface
{
    protected ?string $id;
    protected ?string $job;

    public function __construct(string $id, string $email, ?string $firstName = null, ?string $lastName = null, array $mailingLists = [])
    {
        parent::__construct($email, $firstName, $lastName, $mailingLists);

        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    //    public function getSubscriberVerified(string $id): SubscriberVerified
    //    {
    //        return new SubscriberVerified($id, $this->email, $this->firstName, $this->lastName, $this->mailingLists);
    //    }

    public function isVerified(): bool
    {
        return true;
    }

    public function isDraft(): bool
    {
        return false;
    }

    public function isAccepted(): bool
    {
        return false;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function setJob(string $job): void
    {
        $this->job = $job;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'mailingLists' => $this->mailingLists,
        ];
    }
}
