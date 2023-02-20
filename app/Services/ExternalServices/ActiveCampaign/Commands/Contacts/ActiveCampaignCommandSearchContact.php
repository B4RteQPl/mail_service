<?php

namespace App\Services\ExternalServices\ActiveCampaign\Commands\Contacts;

use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;
use App\ValueObjects\Email;

class ActiveCampaignCommandSearchContact  extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);

        return $this->client->searchContactByEmail($email);
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => 'Znajdź użytkownika',
                'en' => 'Find a User',
            ],
            'description' => [
                'pl' => 'Zwróć szczegóły o użytkowniku po adresie email',
                'en' => 'Find a user by email address',
            ],
            'parameters' => [
                'email' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Adres email',
                        'en' => 'Email address',
                    ],
                ],
            ],
        ];
    }
}
