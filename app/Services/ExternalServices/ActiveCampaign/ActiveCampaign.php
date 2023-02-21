<?php

namespace App\Services\ExternalServices\ActiveCampaign;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists\ActiveCampaignCommandAddContactToList;
use App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists\ActiveCampaignCommandRemoveContactFromList;

class ActiveCampaign extends AbstractCommandLoader
{
    protected ?ActiveCampaignClient $client = null;

    const ACTIVE_COMMANDS = [
        ActiveCampaignCommandAddContactToList::class => 'addContactToList',
        ActiveCampaignCommandRemoveContactFromList::class => 'removeContactFromList',
    ];

    public function __construct(?ActiveCampaignClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey, string $apiUrl): ActiveCampaign
    {
        $client = new ActiveCampaignClient($apiKey, $apiUrl);

        return new ActiveCampaign($client);
    }

    public function setup()
    {
        return [
            'title' => 'test'
        ];
    }
}
