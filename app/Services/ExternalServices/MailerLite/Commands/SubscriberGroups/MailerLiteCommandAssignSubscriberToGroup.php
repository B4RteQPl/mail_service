<?php

namespace App\Services\ExternalServices\MailerLite\Commands\SubscriberGroups;

use App\Services\ExternalServices\MailerLite\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailerLiteCommandAssignSubscriberToGroup extends AbstractCommand
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
            'actionName' => [
                'pl' => 'Dodaj kontakt do grupy',
                'en' => 'Add contact to group',
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
