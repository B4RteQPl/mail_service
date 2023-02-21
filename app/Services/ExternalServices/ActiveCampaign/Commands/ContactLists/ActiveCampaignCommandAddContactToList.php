<?php


namespace App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists;

use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;
use App\Services\FieldsFormBuilder\Text;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class ActiveCampaignCommandAddContactToList extends AbstractCommand
{
    public function execute(array $params)
    {
        $listId = $params['listId'];
        $email = new Email($params['email']);
        $firstname = new FirstName($params['firstName']) ?? new FirstName('');
        $lastname = new LastName($params['lastName']) ?? new LastName('');

        $contact = $this->client->searchContactByEmail($email);
        if (!$contact) {
            $contact = $this->client->createNewContact($email, $firstname, $lastname);
        }

        return $this->client->updateListStatusForContact($listId, $contact['id'], ActiveCampaignClient::LIST_STATUS_SUBSCRIBED);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dopisz do listy',
                'en' => 'Add to list',
            ],
            'fields' => [
                'listId' => [
                    'type' => 'select',
                    'options' => $this->client->retrieveAllLists(),
                    'placeholder' => [
                        'pl' => 'Do jakiej listy dopisać?',
                        'en' => 'To which list add contact?'
                    ],
                ],
            ],
        ];
    }
}
