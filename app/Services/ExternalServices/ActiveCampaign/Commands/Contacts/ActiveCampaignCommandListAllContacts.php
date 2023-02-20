<?php

namespace App\Services\ExternalServices\ActiveCampaign\Commands\Contacts;

use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;

class ActiveCampaignCommandListAllContacts extends AbstractCommand
{
    public function execute()
    {
        return $this->client->listAllContacts();
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => 'Pobierz użytkownika',
                'en' => 'Get a User',
            ],
            'description' => [
                'pl' => 'Zwróć szczegóły o użytkowniku',
                'en' => 'Returns details about a member of a workspace',
            ],
            'parameters' => [],
        ];
    }
}
