<?php

namespace App\Services\ExternalServices\GetResponse\Commands\Campaigns;

use App\Services\ExternalServices\GetResponse\Commands\AbstractCommand;

class GetResponseCommandGetCampaignList extends AbstractCommand
{
    public function execute()
    {
        return $this->client->getCampaignList();
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => '',
                'en' => '',
            ],
            'description' => [
                'pl' => '',
                'en' => '',
            ],
            'parameters' => [

            ],
        ];
    }
}
