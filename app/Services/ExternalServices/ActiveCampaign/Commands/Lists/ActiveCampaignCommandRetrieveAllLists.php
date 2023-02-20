<?php

namespace App\Services\ExternalServices\ActiveCampaign\Commands\Lists;

use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;

class ActiveCampaignCommandRetrieveAllLists extends AbstractCommand
{
    public function execute(): ?array
    {
        return $this->client->retrieveAllLists();
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => 'Pobierz listy',
                'en' => 'Get Lists',
            ],
            'description' => [
                'pl' => 'Zwraca listę list kontaktów',
                'en' => 'Returns a list of lists of contacts',
            ],
            'parameters' => [],
        ];
    }
}
