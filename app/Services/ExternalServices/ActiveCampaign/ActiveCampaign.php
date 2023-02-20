<?php

namespace App\Services\ExternalServices\ActiveCampaign;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists\ActiveCampaignCommandAddContactToList;
use App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists\ActiveCampaignCommandRemoveContactFromList;
use App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists\ActiveCampaignCommandUpdateListStatusForContact;
use App\Services\ExternalServices\ActiveCampaign\Commands\Contacts\ActiveCampaignCommandCreateNewContact;
use App\Services\ExternalServices\ActiveCampaign\Commands\Contacts\ActiveCampaignCommandListAllContacts;
use App\Services\ExternalServices\ActiveCampaign\Commands\Contacts\ActiveCampaignCommandSearchContact;
use App\Services\ExternalServices\ActiveCampaign\Commands\Lists\ActiveCampaignCommandRetrieveAllLists;

class ActiveCampaign extends AbstractCommandLoader
{
    protected ?ActiveCampaignClient $client = null;

    const ACTIVE_COMMANDS = [
        ActiveCampaignCommandSearchContact::class => 'searchContact',
        ActiveCampaignCommandCreateNewContact::class => 'createNewContact',
        ActiveCampaignCommandRetrieveAllLists::class => 'retrieveAllLists',
        ActiveCampaignCommandUpdateListStatusForContact::class => 'updateListStatusForContact',
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
