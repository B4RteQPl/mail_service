<?php

namespace App\Services\ExternalServices\GetResponse\Commands\Contacts;

use App\Services\ExternalServices\GetResponse\Commands\AbstractCommand;
use App\ValueObjects\Email;

class GetResponseCommandCreateContact extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $campaignId = $params['campaignId'];

        return $this->client->createContact($campaignId, $email);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dodaj kontakt do kampanii',
                'en' => 'Add contact to campaign',
            ],
            'fields' => [
                'campaignId' => [
                    'type' => 'select',
                    'options' => $this->client->getCampaignList(),
                    'placeholder' => [
                        'pl' => 'Wybierz kampanię',
                        'en' => 'Select campaign'
                    ],
                ],
            ],
        ];
    }
}
