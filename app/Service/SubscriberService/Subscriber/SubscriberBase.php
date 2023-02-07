<?php

namespace App\Service\SubscriberService\Subscriber;

use App\Interfaces\SubscriberService\Subscriber\SubscriberBaseInterface;
use App\Interfaces\SubscriberService\Subscriber\SubscriberMailingListInterface;
use App\Service\SubscriberService\MailingList\MailingList;

class SubscriberBase implements SubscriberBaseInterface, SubscriberMailingListInterface
{

    protected string $email;
    protected ?string $firstName;
    protected ?string $lastName;
    protected ?array $mailingLists;

    public function __construct(string $email, ?string $firstName = null, ?string $lastName = null, array $mailingLists = [])
    {
        $this->assertEmail($email);
        $this->assertMailingLists($mailingLists);

        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mailingLists = $mailingLists;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->assertEmail($email);

        $this->email = $email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }


    // this can be an array of MailingLists items
    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array
    {
        return $this->mailingLists;
    }

    /**
     * @param MailingList[] $mailingLists
     */
    public function setMailingLists(array $mailingLists): void
    {
        if (MailingList::isInvalidArray($mailingLists)) {
            throw new \InvalidArgumentException('mailingLists array stores only MailingList types');
        }

        $this->mailingLists = $mailingLists;
    }

    public function addMailingList(MailingList $mailingList): void
    {
        if ($this->hasMailingList($mailingList)) {
            return;
        }

        $this->mailingLists[] = $mailingList;
    }

    public function deleteMailingList(MailingList $mailingList): void
    {
        $this->mailingLists = array_filter($this->mailingLists, function ($group) use ($mailingList) {
            return $group->getId() !== $mailingList->getId();
        });
    }

    public function hasMailingList(MailingList $mailingList): bool
    {
        // contains $mailingList with same id
        foreach ($this->mailingLists as $group) {
            if ($group->getId() === $mailingList->getId()) {
                return true;
            }
        }

        return in_array($mailingList, $this->mailingLists, true);
    }

    public function hasNoMailingLists(): bool
    {
        return empty($this->mailingLists);
    }

    private function assertEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email is invalid');
        }
    }

    private function assertMailingLists(array $mailingLists)
    {
        if (MailingList::isInvalidArray($mailingLists)) {
            throw new \InvalidArgumentException('Cannot set account groups array, because array has invalid group');
        }
    }

    public function getSubscriberVerified(string $id): SubscriberVerified
    {
        return new SubscriberVerified($id, $this->email, $this->firstName, $this->lastName, $this->mailingLists);
    }

    public function getSubscriberAccepted(string $jobId, string $status): SubscriberAccepted
    {
        return new SubscriberAccepted($jobId, $status, $this->email, $this->firstName, $this->lastName, $this->mailingLists);
    }
}
