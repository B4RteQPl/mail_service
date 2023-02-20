<?php

namespace App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups;

use App\Services\ExternalServices\MailerLiteClassic\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailerLiteClassicCommandGetListAllGroups extends AbstractCommand
{
    public function execute()
    {
        return $this->client->getListAllGroups();
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
