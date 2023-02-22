<?php

namespace App\Services\ExternalServices\Sendgrid;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\Sendgrid\Client\SendgridClient;
use App\Services\ExternalServices\Sendgrid\Commands\ContactLists\SendgridCommandAddContactToList;
use App\Services\ExternalServices\Sendgrid\Commands\ContactLists\SendgridCommandRemoveContactFromList;

class Sendgrid extends AbstractCommandLoader
{
    protected ?SendgridClient $client = null;

    const ACTIVE_COMMANDS = [
        SendgridCommandAddContactToList::class => 'addContactToList',
        SendgridCommandRemoveContactFromList::class => 'removeContactFromList',
    ];

    public function __construct(?SendgridClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey): Sendgrid
    {
        $client = new SendgridClient($apiKey);

        return new Sendgrid($client);
    }
}
