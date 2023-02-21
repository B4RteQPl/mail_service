<?php


namespace App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists;

use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;
use App\ValueObjects\Email;

/**
 * @url https://developers.activecampaign.com/reference/create-a-new-contact
 */
class ActiveCampaignCommandRemoveContactFromList extends AbstractCommand
{
    public function execute(array $params)
    {
        $listId = $params['listId'];
        $email = new Email($params['email']);

        $contact = $this->client->searchContactByEmail($email);
        if (!$contact) {
            // todo return null is bad practice! Change to exception
            return null;
        }

        return $this->client->updateListStatusForContact($listId, $contact['id'], ActiveCampaignClient::LIST_STATUS_UNSUBSCRIBED);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Usuń z listy',
                'en' => 'Remove from List',
            ],
            'fields' => [
                'listId' => [
                    'type' => 'select',
                    'options' => $this->client->retrieveAllLists(),
                    'placeholder' => [
                        'pl' => 'Z jakiej listy usunąć?',
                        'en' => 'From which list remove?'
                    ],
                ],
            ],
        ];
    }
}
