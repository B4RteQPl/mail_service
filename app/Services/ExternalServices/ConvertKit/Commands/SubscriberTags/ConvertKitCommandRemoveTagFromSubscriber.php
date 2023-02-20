<?php

namespace App\Services\ExternalServices\ConvertKit\Commands\SubscriberTags;

use App\Services\ExternalServices\ConvertKit\Commands\AbstractCommand;
use App\ValueObjects\Email;

/**
 * @url https://developers.activecampaign.com/reference/create-a-new-contact
 */
class ConvertKitCommandRemoveTagFromSubscriber extends AbstractCommand
{
    public function execute(array $params)
    {
        $tagId = $params['tagId'];
        $email = new Email($params['email']);

        try {
            $subscriber = $this->client->listSubscribers($email);
            if (!$subscriber) {
                return null;
            }

            return $this->client->removeTagFromSubscriber($subscriber['id'], $tagId);
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
