<?php

namespace App\Services\ExternalServices\ConvertKit\Commands\SubscriberTags;

use App\Services\ExternalServices\ConvertKit\Commands\AbstractCommand;
use App\ValueObjects\Email;

class ConvertKitCommandAddSubscriberToTag extends AbstractCommand
{
    public function execute(array $params)
    {
        $tagId = $params['tagId'];
        $email = new Email($params['email']);

        return $this->client->tagSubscriber($email, $tagId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dodaj kontakt z tagiem',
                'en' => 'Add contact with tag',
            ],
            'fields' => [
                'tagId' => [
                    'type' => 'select',
                    'options' => $this->client->listTags(),
                    'placeholder' => [
                        'pl' => 'Wybierz tag do dodania',
                        'en' => 'Select tag to add'
                    ],
                ],
            ],
        ];
    }
}
