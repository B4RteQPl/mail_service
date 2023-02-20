<?php

namespace App\Services\ExternalServices\GetResponse\Commands\Contacts;

use App\Services\ExternalServices\GetResponse\Commands\AbstractCommand;
use App\ValueObjects\Email;

class GetResponseCommandDeleteContact extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $campaignId = $params['campaignId'];

        return $this->client->deleteContact($campaignId, $email);
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
