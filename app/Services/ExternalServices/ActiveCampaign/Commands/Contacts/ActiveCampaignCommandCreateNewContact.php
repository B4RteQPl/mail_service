<?php


namespace App\Services\ExternalServices\ActiveCampaign\Commands\Contacts;

use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

/**
 * @url https://developers.activecampaign.com/reference/create-a-new-contact
 */
class ActiveCampaignCommandCreateNewContact extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $firstname = new FirstName($params['firstName']);
        $lastname = new LastName($params['lastName']);

        return $this->client->createNewContact($email, $firstname, $lastname);
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
