<?php


namespace App\Services\ExternalServices\CircleSo\Commands\Accounts;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;

class CircleSoCommandGetCommunityList extends AbstractCommand
{
    public function execute()
    {
        return $this->client->getCommunityList();
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Wybierz społeczność',
                'en' => 'Select community',
            ],
            'fields' => [
                'listId' => [
                    'type' => 'select',
                    'options' => $this->client->getCommunityList(),
                    'placeholder' => [
                        'pl' => '',
                        'en' => 'To which list add contact?'
                    ],
                ],
            ],
        ];
    }
}
