<?php

namespace App\Services\ExternalServices\Sendgrid\Commands\Contacts;

use App\Services\ExternalServices\Sendgrid\Commands\AbstractCommand;
use App\ValueObjects\Email;

class SendgridCommandGetContactsByEmail extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);

        return $this->client->getContactsByEmail($email);
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
