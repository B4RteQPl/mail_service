<?php

namespace App\Interfaces\SubscriberService\MailProvider;

use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

interface MailProviderInterface
{
    public function __construct(string $apiKey, ?string $apiUrl);

    public function isConnectionOk(): bool;

    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array;

    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified;
    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified;
//    public function addSubscriberToMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;
    public function addSubscriberDraftToMailingList(SubscriberDraft $subscriber, MailingList $mailingList): SubscriberVerified;
    public function addSubscriberVerifiedToMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;
    public function deleteSubscriberFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;

    public function getMailProviderType(): string;

    // TODO: find better approach to get testing data for each provider
    public function getTestingGroupId(): string;
    public function getTestingSecondGroupId(): string;
}
