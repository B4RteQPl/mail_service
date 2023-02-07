<?php

namespace App\Service\SubscriberService\Subscriber;

use App\Interfaces\SubscriberService\Subscriber\SubscriberDraftInterface;

final class SubscriberDraft extends SubscriberBase implements SubscriberDraftInterface
{

    public function getSubscriberVerified(string $id): SubscriberVerified
    {
        return new SubscriberVerified($id, $this->email, $this->firstName, $this->lastName, $this->mailingLists);
    }

    public function isVerified(): bool
    {
        return false;
    }

    public function isDraft(): bool
    {
        return true;
    }

    public function isAccepted(): bool
    {
        return false;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'mailingLists' => $this->mailingLists,
        ];
    }
}
