<?php

namespace App\Services\ExternalServices\Sendgrid\Commands\Lists;

use App\Services\ExternalServices\Sendgrid\Commands\AbstractCommand;

class SendgridCommandGetAllLists extends AbstractCommand
{
    public function execute()
    {
        return $this->client->getAllLists();
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
