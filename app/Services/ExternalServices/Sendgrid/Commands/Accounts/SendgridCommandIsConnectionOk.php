<?php

namespace App\Services\ExternalServices\Sendgrid\Commands\Accounts;

use App\Services\ExternalServices\Sendgrid\Commands\AbstractCommand;

class SendgridCommandIsConnectionOk extends AbstractCommand
{
    public function execute()
    {
        return $this->client->isConnectionOk();
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
