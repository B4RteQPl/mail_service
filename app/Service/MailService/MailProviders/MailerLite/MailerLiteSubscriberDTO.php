<?php

namespace App\Service\MailService\MailProviders\MailerLite;

class MailerLiteSubscriberDTO
{

    static function toArray(array $subscriber): array
    {
        $subscriberDTO =  [
            'id' => $subscriber['id'],
            'email' => $subscriber['email'],
        ];

        // if groups key not exists
        if (array_key_exists('groups', $subscriber)) {
            $subscriberDTO['groups'] = $subscriber['groups'];
        } else {
            $subscriberDTO['groups'] = [];
        }

        return $subscriberDTO;
    }
}
