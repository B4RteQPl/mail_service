<?php


namespace App\Services\ExternalServices\CircleSo\Commands\Spaces;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandGetMemberFromSpace extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);
        $spaceId = $params['spaceId'];

        return $this->client->getMemberFromSpace($communityId, $email, $spaceId);
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => 'Utwórz nowego użytkownika',
                'en' => 'Create a new user',
            ],
            'description' => [
                'pl' => 'Tworzy nowego użytkownika w ActiveCampaign',
                'en' => 'Creates a new user in ActiveCampaign',
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
                'firstName' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Imię',
                        'en' => 'First name',
                    ],
                ],
                'lastName' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Nazwisko',
                        'en' => 'Last name',
                    ],
                ],
            ],
        ];
    }
}
