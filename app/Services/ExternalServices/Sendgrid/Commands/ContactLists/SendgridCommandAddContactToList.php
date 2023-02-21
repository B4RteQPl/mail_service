<?php

namespace App\Services\ExternalServices\Sendgrid\Commands\ContactLists;

use App\Services\ExternalServices\Sendgrid\Commands\AbstractCommand;
use App\ValueObjects\Email;

class SendgridCommandAddContactToList extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $listId = $params['listId'];

        return $this->client->addContactToList($email, $listId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dodaj kontakt do listy',
                'en' => 'Add contact to list',
            ],
            'fields' => [
                'listId' => [
                    'type' => 'select',
                    'options' => $this->client->getAllLists(),
                    'placeholder' => [
                        'pl' => 'Wybierz grupę',
                        'en' => 'Select group'
                    ],
                ],
            ],
        ];
    }
}
