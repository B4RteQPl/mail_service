<?php

namespace App\Services\ExternalServices\Mailchimp\Commands\Accounts;

use App\Services\ExternalServices\Mailchimp\Commands\AbstractCommand;

class MailchimpCommandIsConnectionOk extends AbstractCommand
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
