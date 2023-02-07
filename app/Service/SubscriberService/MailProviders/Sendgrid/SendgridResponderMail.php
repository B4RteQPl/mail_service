<?php

namespace App\Service\SubscriberService\MailProviders\Sendgrid;

use App\Interfaces\SubscriberService\MailProvider\MailProviderResponderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberAccepted;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

class SendgridResponderMail implements MailProviderResponderInterface
{

    protected $response;

    private function __construct($response)
    {
        $this->response = $response;
        dump($response);
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

        foreach ($this->response['result'] as $group) {
            $mailingList = new MailingList($group['id'], $group['name'], SendgridMailProvider::MAIL_PROVIDER_TYPE);

            $mailingLists[] = $mailingList;
        }

        return $mailingLists;
    }

    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified
    {
        $id = $this->response['id'];
        // todo set request confirmation status ???

        return $subscriber->getSubscriberVerified($id);
    }

    public function getAcceptedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberAccepted
    {
        $jobId = $this->response['job_id'];
        // todo set request confirmation status ???

        $subscriber = $subscriber->getSubscriberAccepted($jobId, 'verification_required');
        dump('----------');
        dump($subscriber);
//        $subscriber->setJob($id);

        return $subscriber;
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
