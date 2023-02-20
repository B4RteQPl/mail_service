<?php

namespace App\Services\ExternalServices\GetResponse;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\GetResponse\Client\GetResponseClient;
use App\Services\ExternalServices\GetResponse\Commands\Accounts\GetResponseCommandIsConnectionOk;
use App\Services\ExternalServices\GetResponse\Commands\CampaignContacts\GetResponseCommandGetContactsFromCampaign;
use App\Services\ExternalServices\GetResponse\Commands\Campaigns\GetResponseCommandGetCampaignList;
use App\Services\ExternalServices\GetResponse\Commands\Contacts\GetResponseCommandCreateContact;
use App\Services\ExternalServices\GetResponse\Commands\Contacts\GetResponseCommandDeleteContact;

class GetResponse extends AbstractCommandLoader
{
    protected ?GetResponseClient $client = null;

    const ACTIVE_COMMANDS = [
        GetResponseCommandIsConnectionOk::class => 'isConnectionOk',
        GetResponseCommandGetCampaignList::class => 'getCampaignList',
        GetResponseCommandGetContactsFromCampaign::class => 'getContactsFromCampaign',
        GetResponseCommandCreateContact::class => 'createContact',
        GetResponseCommandDeleteContact::class => 'deleteContact',
    ];

    public function __construct(?GetResponseClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey): GetResponse
    {
        $client = new GetResponseClient($apiKey);

        return new GetResponse($client);
    }
}
