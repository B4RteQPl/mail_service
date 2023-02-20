<?php

namespace App\Services\ExternalServices\GetResponse\Commands\Accounts;

use App\Services\ExternalServices\GetResponse\Commands\AbstractCommand;

class GetResponseCommandIsConnectionOk extends AbstractCommand
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
