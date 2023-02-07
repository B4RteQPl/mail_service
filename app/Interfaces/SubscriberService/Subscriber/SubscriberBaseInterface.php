<?php

namespace App\Interfaces\SubscriberService\Subscriber;

use App\Service\SubscriberService\Subscriber\SubscriberAccepted;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

interface SubscriberBaseInterface
{
    public function getFirstName();
    public function setFirstName(string $firstname): void;
    public function getLastName();
    public function setLastName(string $lastname): void;
    public function getEmail();
    public function setEmail(string $email): void;

    public function getSubscriberVerified(string $id): SubscriberVerified;
    public function getSubscriberAccepted(string $jobId, string $status): SubscriberAccepted;
}
