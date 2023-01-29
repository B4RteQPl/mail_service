<?php

namespace App\Service\MailService\MailProviders\MailerLiteClassic;

class MailerLiteClassicSubscriberDTO
{

    static function toArray(array $subscriber): array
    {
        $subscriberDTO =  [
            'id' => $subscriber['id'],
            'email' => $subscriber['email'],
        ];

        // if groups key not exists
        if (array_key_exists('groups', $subscriber)) {
            $groupsIDs = array_column($subscriber['groups'], 'id');
            $subscriberDTO['groups'] = $groupsIDs;
        } else {
            $subscriberDTO['groups'] = [];
        }

        return $subscriberDTO;
    }
}
