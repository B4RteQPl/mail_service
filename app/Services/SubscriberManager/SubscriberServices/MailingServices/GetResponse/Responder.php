<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\GetResponse;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\MailingServices\MailResponderInterface;
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

        foreach ($this->response as $group) {
            $subscriberList = new MailingList($group['campaignId'], $group['name'], DeliveryService::TYPE);

            $subscriberLists[] = $subscriberList;
        }

        return $subscriberLists;
    }

    public function updateSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        if (empty($this->response)) {
            $subscriber->setStatusVerificationPending();
            // todo in this place maybe it's again good to put subscriber to verification
            return $subscriber;
        }

        $id = $this->response[0]['contactId'];
        $subscriber->setStatusVerified($id);

        $campaign = $this->response[0]['campaign'];
        $subscriberList = new MailingList($campaign['campaignId'], $campaign['name'], DeliveryService::TYPE);

        if (!$subscriber->mailingLists->has($subscriberList)) {
            $subscriber->mailingLists->add($subscriberList);
        }


        return $subscriber;
    }

    public function updateSubscriberAfterAddToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        dump($this->response);
        $subscriberList->setStatusVerificationPending();
        $subscriber->mailingLists()->add($subscriberList);

        return $subscriber;
    }

    public function updateSubscriberAfterDeleteFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        dump($this->response);
        $subscriber->mailingLists()->delete($subscriberList);

        return $subscriber;
    }
}
