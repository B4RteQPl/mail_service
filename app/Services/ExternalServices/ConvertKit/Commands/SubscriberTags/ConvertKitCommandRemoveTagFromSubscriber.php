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
            'actionName' => [
                'pl' => 'Usuń kontakt z tagiem',
                'en' => 'Remove contact with tag',
            ],
            'fields' => [
                'tagId' => [
                    'type' => 'select',
                    'options' => $this->client->listTags(),
                    'placeholder' => [
                        'pl' => 'Wybierz tag do usunięcia',
                        'en' => 'Select tag to remove'
                    ],
                ],
            ],
        ];
    }
}
