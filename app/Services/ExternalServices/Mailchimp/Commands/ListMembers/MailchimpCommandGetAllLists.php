<?php

namespace App\Services\ExternalServices\Mailchimp\Commands\ListMembers;

use App\Services\ExternalServices\Mailchimp\Commands\AbstractCommand;

class MailchimpCommandGetAllLists extends AbstractCommand
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
