<?php

namespace App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups;

use App\Services\ExternalServices\MailerLiteClassic\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailerLiteClassicCommandAssignSubscriberToGroup extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $groupId = $params['groupId'];

        $subscriber = $this->client->fetchSubscriber($email);
        $subscriberId = $subscriber['id'];

        return $this->client->assignSubscriberToGroup($subscriberId, $groupId);
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
