<?php

namespace App\Interfaces\SubscriberService;

use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

interface SubscriberServiceInterface
{
    public function __construct(MailProviderInterface $mailProvider);

    public function isConnectionOk(): bool;
    public function getMailingLists(): array;
    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified;
    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified;
    public function addSubscriberToMailingList(SubscriberDraft|SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;
    public function deleteSubscriberFromMailingList(SubscriberDraft|SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;
}
