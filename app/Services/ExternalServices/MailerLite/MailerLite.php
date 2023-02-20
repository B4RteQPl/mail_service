<?php

namespace App\Services\ExternalServices\MailerLite;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\MailerLite\Client\MailerLiteClient;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteCommandAssignSubscriberToGroup;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteCommandGetListAllGroups;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteCommandUnAssignSubscriberFromGroup;
use App\Services\ExternalServices\MailerLite\Commands\Subscribers\MailerLiteCommandCreateSubscriber;
use App\Services\ExternalServices\MailerLite\Commands\Subscribers\MailerLiteCommandFetchSubscriber;

class MailerLite extends AbstractCommandLoader
{
    protected ?MailerLiteClient $client = null;

    const ACTIVE_COMMANDS = [
        MailerLiteCommandCreateSubscriber::class => 'createSubscriber',
        MailerLiteCommandFetchSubscriber::class => 'fetchSubscriber',
        MailerLiteCommandGetListAllGroups::class => 'getListAllGroups',
        MailerLiteCommandAssignSubscriberToGroup::class => 'assignSubscriberToGroup',
        MailerLiteCommandUnAssignSubscriberFromGroup::class => 'unAssignSubscriberFromGroup',
    ];

    public function __construct(?MailerLiteClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey): MailerLite
    {
        $client = new MailerLiteClient($apiKey);

        return new MailerLite($client);
    }
}
