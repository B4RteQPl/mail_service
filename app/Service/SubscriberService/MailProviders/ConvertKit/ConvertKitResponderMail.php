<?php

namespace App\Service\SubscriberService\MailProviders\ConvertKit;

use App\Interfaces\SubscriberService\MailProvider\MailProviderResponderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

class ConvertKitResponderMail implements MailProviderResponderInterface
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

        foreach ($this->response['tags'] as $group) {
            $mailingList = new MailingList($group['id'], $group['name'], ConvertKitMailProvider::MAIL_PROVIDER_TYPE);

            $mailingLists[] = $mailingList;
        }

        return $mailingLists;
    }

    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified
    {
        $id = $this->response['subscribers'][0]['id'];

        return $subscriber->getSubscriberVerified($id);
    }

    public function getVerifiedSubscriberAfterAddToMailingList(SubscriberVerified|SubscriberDraft $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $id = $this->response['subscription']['subscriber']['id'];

        $subscriber = $subscriber->getSubscriberVerified($id);
        $subscriber->addMailingList($mailingList);

        return $subscriber;
    }

    public function getVerifiedSubscriberAfterDeleteFromMailingList(SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $subscriber->deleteMailingList($mailingList);

        return $subscriber;
    }
}
