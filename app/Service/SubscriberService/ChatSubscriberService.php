<?php

namespace App\Service\SubscriberService;

use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

class ChatSubscriberService
{

    private MailProviderInterface $provider;

    public function __construct(MailProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function isConnectionOk(): bool
    {
        return $this->provider->isConnectionOk();
    }

    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        return $this->provider->addSubscriber($subscriber);
    }

    public function deleteSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        return $this->provider->deleteSubscriber($subscriber);
    }

    public function getProvider()
    {
        return $this->provider;
    }
}
