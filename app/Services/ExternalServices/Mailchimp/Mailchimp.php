<?php

namespace App\Services\ExternalServices\Mailchimp;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\Mailchimp\Client\MailchimpClient;
use App\Services\ExternalServices\Mailchimp\Commands\ListMembers\MailchimpCommandAddListMember;
use App\Services\ExternalServices\Mailchimp\Commands\ListMembers\MailchimpCommandDeleteListMember;

class Mailchimp extends AbstractCommandLoader
{
    protected ?MailchimpClient $client = null;

    const ACTIVE_COMMANDS = [
        MailchimpCommandDeleteListMember::class => 'deleteListMember',
        MailchimpCommandAddListMember::class => 'addListMember',
    ];

    public function __construct(?MailchimpClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey): Mailchimp
    {
        $client = new MailchimpClient($apiKey);

        return new Mailchimp($client);
    }
}
