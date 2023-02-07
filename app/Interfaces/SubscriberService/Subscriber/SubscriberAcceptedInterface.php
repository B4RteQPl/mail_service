<?php

namespace App\Interfaces\SubscriberService\Subscriber;

use App\Service\SubscriberService\Subscriber\SubscriberVerified;

interface SubscriberAcceptedInterface
{
    public function __construct(string $jobId, string $status, string $email, string $firstName, string $lastName, array $mailingLists);

    public function getJobId();
    public function setJobId(string $jobId): void;

    public function getJobStatus();
    public function setJobStatus(string $status): void;

    public function isVerified(): bool;
    public function isDraft(): bool;
    public function isAccepted(): bool;

    public function toArray(): array;
}
