<?php

namespace App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups;

use App\Services\ExternalServices\MailerLite\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailerLiteCommandUnAssignSubscriberFromGroup extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $groupId = $params['groupId'];

        $subscriber = $this->client->fetchSubscriber($email);
        $subscriberId = $subscriber['id'];

        return $this->client->unAssignSubscriberFromGroup($subscriberId, $groupId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Usuń kontakt z grupy',
                'en' => 'Remove contact from group',
            ],
            'fields' => [
                'groupId' => [
                    'type' => 'select',
                    'options' => $this->client->getListAllGroups(),
                    'placeholder' => [
                        'pl' => 'Wybierz grupę',
                        'en' => 'Select group'
                    ],
                ],
            ],
        ];
    }
}
