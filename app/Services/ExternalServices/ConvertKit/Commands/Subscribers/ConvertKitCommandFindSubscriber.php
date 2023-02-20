<?php

namespace App\Services\ExternalServices\ConvertKit\Commands\Subscribers;

use App\Services\ExternalServices\ConvertKit\Commands\AbstractCommand;
use App\ValueObjects\Email;

/**
 * @url https://developers.activecampaign.com/reference/create-a-new-contact
 */
class ConvertKitCommandFindSubscriber extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);

        return $this->client->listSubscribers($email);
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
