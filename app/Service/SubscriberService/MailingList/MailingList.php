<?php

namespace App\Service\SubscriberService\MailingList;

use App\Interfaces\SubscriberService\MailingList\MailingListInterface;

class MailingList implements MailingListInterface
{

    private string $id;
    private string $name;
    private string $mailProviderType;

    public function __construct(string $id, string $name, string $mailProviderType)
    {
        $this->assertId($id);
        $this->assertName($name);
        $this->assertMailProviderType($mailProviderType);

        $this->id = $id;
        $this->name = $name;
        $this->mailProviderType = $mailProviderType;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->assertId($id);

        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->assertName($name);

        $this->name = $name;
    }

    public function getMailProviderType():?string
    {
        return $this->mailProviderType;
    }

    public function setMailProviderType(string $mailProviderType): void
    {
        $this->assertMailProviderType($mailProviderType);

        $this->mailProviderType = $mailProviderType;
    }

    public function hasMailProviderType(string $mailProviderType): bool
    {
        return $this->mailProviderType === $mailProviderType;
    }

    public static function isInvalid($mailingList): bool
    {
        if (!$mailingList instanceof MailingList) {
            return true;
        }

        return false;
    }

    public static function isInvalidArray(array $mailingLists): bool
    {
        foreach ($mailingLists as $mailingList) {
            if (MailingList::isInvalid($mailingList)) {
                return true;
            }
        }

        return false;
    }

    private function assertId(string $id): void
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Id cannot be empty');
        }
    }

    private function assertName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
    }

    private function assertMailProviderType(string $mailProviderType): void
    {
        if (empty($mailProviderType)) {
            throw new \InvalidArgumentException('Mail provider type cannot be empty');
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mailProviderType' => $this->mailProviderType,
        ];
    }
}
