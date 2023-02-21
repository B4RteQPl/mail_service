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

    static public function configureIntegration()
    {
        return [
            'fields' => [
                'integrationName' => [
                    'type' => 'string',
                    'placeholder' => [
                        'pl' => 'Nazwij konfiguracje',
                        'en' => 'Name your configuration'
                    ],
                ],
                'apiUrl' => [
                    'type' => 'string',
                    'placeholder' => [
                        'pl' => 'Podaj nazwę użytkownika',
                        'en' => 'Enter username'
                    ],
                    'hint' => [
                        'pl' => 'Znajdziesz go w <a href="https://developers.activecampaign.com/reference/url" target="_blank">Ustawieniach API</a>',
                        'en' => 'You can find it in <a href="https://developers.activecampaign.com/reference/url" target="_blank">API Settings</a>'
                    ],
                ],
                'apiKey' => [
                    'type' => 'string',
                    'placeholder' => [
                        'pl' => 'Podaj klucz API',
                        'en' => 'Enter API key'
                    ],
                    'hint' => [
                        'pl' => 'Znajdziesz go w <a href="https://developers.activecampaign.com/reference/authentication" target="_blank">Ustawieniach API</a>',
                        'en' => 'You can find it in <a href="https://developers.activecampaign.com/reference/authentication" target="_blank">API Settings</a>'
                    ],
                ],
            ],
        ];
    }

    static public function setClient(string $apiKey, string $apiUrl): ActiveCampaign
    {
        $client = new ActiveCampaignClient($apiKey, $apiUrl);

        return new ActiveCampaign($client);
    }
}
