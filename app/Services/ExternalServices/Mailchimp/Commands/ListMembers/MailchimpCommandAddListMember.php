<?php

namespace App\Services\ExternalServices\Mailchimp\Commands\ListMembers;

use App\Services\ExternalServices\Mailchimp\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailchimpCommandAddListMember extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $listId = $params['listId'];

        return $this->client->addListMember($email, $listId);
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
                        'pl' => 'Wybierz listę',
                        'en' => 'Select list'
                    ],
                ],
            ],
        ];
    }
}
