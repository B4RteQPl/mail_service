<?php

namespace App\Interfaces\SubscriberService\MailingList;

interface MailingListInterface
{
    public function __construct(string $id, string $name, string $mailProviderType);

    public function getId(): ?string;
    public function setId(string $id): void;
    public function getName(): ?string;
    public function setName(string $name): void;

    public function getMailProviderType():?string;
    public function setMailProviderType(string $mailProviderType): void;
    public function hasMailProviderType(string $mailProviderType): bool;

    public static function isInvalidArray(array $mailingLists): bool;

    public function toArray(): array;
}
