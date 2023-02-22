<?php

namespace App\Services\ExternalServices\Sendgrid\Commands\ContactLists;

use App\Services\ExternalServices\Sendgrid\Commands\AbstractCommand;
use App\ValueObjects\Email;

class SendgridCommandRemoveContactFromList extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $listId = $params['listId'];

        return $this->client->removeContactFromList($email, $listId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Usuń kontakt z listy',
                'en' => 'Remove contact from list',
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
