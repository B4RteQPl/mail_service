<?php

namespace App\Services\ExternalServices\MailerLiteClassic;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\MailerLite\Commands\Accounts\MailerLiteClassicCommandIsConnectionOk;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteClassicCommandAssignSubscriberToGroup;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteClassicCommandGetListAllGroups;
use App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups\MailerLiteClassicCommandUnAssignSubscriberFromGroup;
use App\Services\ExternalServices\MailerLite\Commands\Subscribers\MailerLiteClassicCommandCreateSubscriber;
use App\Services\ExternalServices\MailerLite\Commands\Subscribers\MailerLiteClassicCommandFetchSubscriber;
use App\Services\ExternalServices\MailerLiteClassic\Client\MailerLiteClassicClient;

class MailerLiteClassic extends AbstractCommandLoader
{
    protected ?MailerLiteClassicClient $client = null;

    const ACTIVE_COMMANDS = [
        MailerLiteClassicCommandIsConnectionOk::class => 'isConnectionOk',
        MailerLiteClassicCommandCreateSubscriber::class => 'createSubscriber',
        MailerLiteClassicCommandFetchSubscriber::class => 'fetchSubscriber',
        MailerLiteClassicCommandGetListAllGroups::class => 'getListAllGroups',
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
