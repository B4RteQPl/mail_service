<?php

namespace App\Services\ExternalServices\MailerLite;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\MailerLite\Client\MailerLiteClient;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteCommandAssignSubscriberToGroup;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteCommandUnAssignSubscriberFromGroup;

class MailerLite extends AbstractCommandLoader
{
    protected ?MailerLiteClient $client = null;

    const ACTIVE_COMMANDS = [
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
