<?php

namespace App\Interfaces\SubscriberService\Subscriber;

use App\Service\SubscriberService\MailingList\MailingList;

interface SubscriberMailingListInterface
{
    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array;
    /**
     * @param MailingList[] $mailingLists
     */
    public function setMailingLists(array $mailingLists): void;

    public function addMailingList(MailingList $mailingList): void;
    public function deleteMailingList(MailingList $mailingList): void;

    public function hasMailingList(MailingList $mailingList): bool;
    public function hasNoMailingLists(): bool;
}
