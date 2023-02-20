<?php

namespace App\Services\ExternalServices\MailerLite\Commands\Accounts;

use App\Services\ExternalServices\MailerLiteClassic\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailerLiteClassicCommandIsConnectionOk extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $listId = $params['list_id'];

        return $this->client->addListMember($email, $listId);
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
