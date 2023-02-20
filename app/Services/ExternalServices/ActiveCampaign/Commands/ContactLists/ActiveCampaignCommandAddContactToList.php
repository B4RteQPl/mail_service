<?php


namespace App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists;

use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;
use App\Services\FieldsFormBuilder\Text;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

/**
 * @url https://developers.activecampaign.com/reference/create-a-new-contact
 */
class ActiveCampaignCommandAddContactToList extends AbstractCommand
{
    public function execute(array $params)
    {
        $listId = $params['listId'];
        $email = new Email($params['email']);
        $firstname = new FirstName($params['firstName']) ?? new FirstName('');
        $lastname = new LastName($params['lastName']) ?? new LastName('');

        try {
            $contact = $this->client->searchContactByEmail($email);
            if (!$contact) {
                $contact = $this->client->createNewContact($email, $firstname, $lastname);
            }

            return $this->client->updateListStatusForContact($listId, $contact['id'], ActiveCampaignClient::LIST_STATUS_SUBSCRIBED);
        } catch (\Exception $e) {
            $this->logException($e);
            return null;
        }
    }

    public function getTranslations()
    {

    }
    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dodaj kontakt do listy',
                'en' => 'Add a contact to List',
            ],
            'parameters' => [
//                'v2ListId' => SelectField
                'listId' => [
                    'type' => 'select',
                    'required' => true,
                    'options' => $this->client->retrieveAllLists(),
                    'placeholder' => [
                        'pl' => 'Wybierz listę',
                        'en' => 'Pick a list'
                    ],
                ],
                'emailv2' => Email::;
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
