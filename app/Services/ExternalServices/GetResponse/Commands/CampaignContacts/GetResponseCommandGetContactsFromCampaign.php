<?php

namespace App\Services\ExternalServices\GetResponse\Commands\CampaignContacts;

use App\Services\ExternalServices\GetResponse\Commands\AbstractCommand;
use App\ValueObjects\Email;

class GetResponseCommandGetContactsFromCampaign extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);

        return $this->client->getContactsFromCampaign($email);
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
