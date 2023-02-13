<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\Sendgrid;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices\MailResponderInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\BaseResponder;

class Responder extends BaseResponder implements MailResponderInterface
{

    public static function for($response): self
    {
        return new self($response->json());
    }

    /**
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array
    {
        $subscriberLists = [];

        foreach ($this->response['result'] as $group) {
            $subscriberList = new MailingList($group['id'], $group['name'], DeliveryService::TYPE);

            $subscriberLists[] = $subscriberList;
        }

        return $subscriberLists;
    }

    public function updateSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $id = $this->response['id'];

        $subscriber->setStatusVerified($id);

        return $subscriber;
    }

    public function getVerificationPendingSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $job = $this->response;

        $subscriber->setStatusVerificationPending($job);

        return $subscriber;
    }

    public function updateSubscriberAfterAddToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $subscriberList->setStatusVerificationPending();
        $subscriber->mailingLists()->add($subscriberList);

        return $subscriber;
    }

    public function updateSubscriberAfterDeleteFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $subscriber->mailingLists()->delete($subscriberList);

        return $subscriber;
    }
}
