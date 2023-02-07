<?php

namespace App\Interfaces\SubscriberService\MailProvider;

use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

interface MailProviderResponderInterface
{
    public static function for($response): self;

    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified;
    public function getVerifiedSubscriberAfterAddToMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;
    public function getVerifiedSubscriberAfterDeleteFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified;
}
