<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\MailChimp;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices\MailResponderInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\BaseResponder;

class Responder extends BaseResponder implements MailResponderInterface
{

    public static function for($response): self
    {
        return new self($response);
    }

    /**
     * @return SubscriberListInterface[]
     */
    public function getSubscriberLists(): array
    {
        $subscriberLists = [];

        foreach ($this->response->lists as $audienceList) {
            $subscriberList = new MailingList($audienceList->id, $audienceList->name, DeliveryService::TYPE);

            $subscriberLists[] = $subscriberList;
        }

        return $subscriberLists;
    }

    public function updateSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $id = $this->response->id;

        $subscriber->setStatusVerified($id);

        return $subscriber;
    }

    public function updateSubscriberFromSearchResult(SubscriberInterface $subscriber): SubscriberInterface
    {
        $id = $this->response->id;

        $subscriber->setStatusVerified($id);
        return $subscriber;
    }

    public function updateSubscriberAfterAddToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $id = $this->response->id;
        $subscriber->setStatusVerified($id);
        $subscriberList->setStatusVerified();

        $subscriber->mailingLists()->add($subscriberList);

        return $subscriber;
    }

    public function updateSubscriberAfterDeleteFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $subscriber->mailingLists()->delete($subscriberList);

        return $subscriber;
    }
}
