<?php

namespace App\Service\SubscriberService\MailProviders\GetResponse;

use App\Interfaces\SubscriberService\MailProvider\MailProviderResponderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

class GetResponseResponderMail implements MailProviderResponderInterface
{

    protected $response;

    private function __construct($response)
    {
        $this->response = $response;
    }

    public static function for($response): self
    {
        return new self($response->json());
    }

    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array
    {
        $mailingLists = [];

        foreach ($this->response as $group) {
            $mailingList = new MailingList($group['campaignId'], $group['name'], GetResponseMailProvider::MAIL_PROVIDER_TYPE);

            $mailingLists[] = $mailingList;
        }

        return $mailingLists;
    }

    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified
    {
        $id = $this->response['data']['id'];

        return $subscriber->getSubscriberVerified($id);
    }

    public function getVerifiedSubscriberAfterAddToMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $subscriber->addMailingList($mailingList);

        return $subscriber;
    }

    public function getVerifiedSubscriberAfterDeleteFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $subscriber->deleteMailingList($mailingList);

        return $subscriber;
    }
}
