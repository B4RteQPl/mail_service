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

        try {
            $contact = $this->client->searchContactByEmail($email);
            if (!$contact) {
                return null;
            }

            return $this->client->updateListStatusForContact($listId, $contact['id'], ActiveCampaignClient::LIST_STATUS_UNSUBSCRIBED);
        } catch (\Exception $e) {
            $this->logException($e);
            return null;
        }
    }

    public function getConfig()
    {
        return [
            'title' => [
                'en' => 'Remove a User from List',
                'pl' => 'Usuń użytkownika z listy',
            ],
            'description' => [
                'pl' => '',
                'en' => '',
            ],
            'parameters' => [
                'listId' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Wybierz listę',
                        'en' => 'Pick a list'
                    ],
                ],
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
