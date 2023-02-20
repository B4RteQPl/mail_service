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
