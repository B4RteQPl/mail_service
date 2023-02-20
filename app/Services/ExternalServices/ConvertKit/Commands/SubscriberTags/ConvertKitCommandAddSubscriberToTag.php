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

        try {
            return $this->client->tagSubscriber($email, $tagId);
        } catch (\Exception $e) {
            $this->logException($e);
            return null;
        }
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
                'email' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Adres email',
                        'en' => 'Email address',
                    ],
                ],
                'firstName' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Imię',
                        'en' => 'First name',
                    ],
                ],
                'lastName' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Nazwisko',
                        'en' => 'Last name',
                    ],
                ],
            ],
        ];
    }
}
