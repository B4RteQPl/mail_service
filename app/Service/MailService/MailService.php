<?php

namespace App\Service\MailService;

use App\Interfaces\MailProviderInterface;

class MailService
{
    /**
     * @var MailProviderInterface
     */
    private $mailProvider;

    public function __construct(MailProviderInterface $mailProvider)
    {
        $this->mailProvider = $mailProvider;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function addSubscriberToGroup(string $email, string $name, string $groupId): array
    {
        if (empty($groupId)) {
            throw new \InvalidArgumentException('groupId is missing');
        }

        if (empty($email)) {
            throw new \InvalidArgumentException('email is missing');
        }

        return $this->mailProvider->addSubscriberToGroup($email, $name, $groupId);
    }

    /**
     * @throws \InvalidArgumentException|bool
     */
    public function deleteSubscriberFromGroup(string $email, string $groupId): bool
    {
        if (empty($groupId)) {
            throw new \InvalidArgumentException('groupId is missing');
        }

        if (empty($email)) {
            throw new \InvalidArgumentException('email is missing');
        }

        return $this->mailProvider->deleteSubscriberFromGroup($email, $groupId);
    }

    public function isSubscriberAssignedToGroup(string $email, string $groupId)
    {
        return $this->mailProvider->isSubscriberAssignedToGroup($email, $groupId);
    }

    public function createGroup(string $name)
    {
        return $this->mailProvider->createGroup($name);
    }

    public function getGroups(): array
    {
        return $this->mailProvider->getGroups();
    }

    public function getSubscriber(string $email): array
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('email is missing');
        }

        return $this->mailProvider->getSubscriber($email);
    }

    public function isConnectionOk(): bool
    {
        return $this->mailProvider->isConnectionOk();
    }

}
