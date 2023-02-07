<?php

namespace App\Service\SubscriberService\Subscriber;

use App\Interfaces\SubscriberService\Subscriber\SubscriberAcceptedInterface;

final class SubscriberAccepted extends SubscriberBase implements SubscriberAcceptedInterface
{
    protected ?string $jobId;
    protected ?string $jobStatus;

    public function __construct(string $jobId, string $jobStatus, string $email, ?string $firstName = null, ?string $lastName = null, array $mailingLists = [])
    {
        parent::__construct($email, $firstName, $lastName, $mailingLists);

        $this->jobId = $jobId;
        $this->jobStatus = $jobStatus;
    }

    public function getJobId(): ?string
    {
        return $this->jobId;
    }

    public function setJobId(string $jobId): void
    {
        $this->jobId = $jobId;
    }

    public function getJobStatus(): ?string
    {
        return $this->jobStatus;
    }

    public function setJobStatus(string $jobStatus): void
    {
        $this->jobStatus = $jobStatus;
    }

    public function isVerified(): bool
    {
        return false;
    }

    public function isDraft(): bool
    {
        return false;
    }

    public function isAccepted(): bool
    {
        return true;
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
