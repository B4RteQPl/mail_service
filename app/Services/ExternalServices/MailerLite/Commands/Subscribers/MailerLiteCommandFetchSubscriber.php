<?php

namespace App\Services\ExternalServices\MailerLite\Commands\Subscribers;

use App\Services\ExternalServices\Mailchimp\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailerLiteCommandFetchSubscriber extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);

        return $this->client->fetchSubscriber($email);
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
