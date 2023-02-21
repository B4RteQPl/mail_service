<?php

namespace App\Services\ExternalServices\MailerLiteClassic;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\MailerLiteClassic\Client\MailerLiteClassicClient;
use App\Services\ExternalServices\MailerLiteClassic\Commands\SubscriberGroups\MailerLiteClassicCommandAssignSubscriberToGroup;
use App\Services\ExternalServices\MailerLiteClassic\Commands\SubscriberGroups\MailerLiteClassicCommandUnAssignSubscriberFromGroup;

class MailerLiteClassic extends AbstractCommandLoader
{
    protected ?MailerLiteClassicClient $client = null;

    const ACTIVE_COMMANDS = [
        MailerLiteClassicCommandAssignSubscriberToGroup::class => 'assignSubscriberToGroup',
        MailerLiteClassicCommandUnAssignSubscriberFromGroup::class => 'unAssignSubscriberFromGroup',
    ];

    public function __construct(?MailerLiteClassicClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey): MailerLiteClassic
    {
        $client = new MailerLiteClassicClient($apiKey);

        return new MailerLiteClassic($client);
    }
}
