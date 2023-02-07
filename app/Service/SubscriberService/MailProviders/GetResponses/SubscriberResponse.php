<?php

namespace App\Service\SubscriberService\MailProviders\GetResponses;

use App\Interfaces\SubscriberResponseInterface;
use App\Service\SubscriberService\Subscriber;

class SubscriberResponse implements SubscriberResponseInterface
{

    static function convert($response, Subscriber $subscriber): array
    {
        $DTO =  [
            'id' => $response['id'],
            'email' => $response['email'],
        ];

        // if groups key not exists
        if (array_key_exists('groups', $response)) {
            $DTO['groups'] = $response['groups'];
        } else {
            $DTO['groups'] = [];
        }


        return $DTO;
    }

    static function getDTOArray(string $email, string $groupId, string $name): array
    {
        return [
            'email' => $email,
            'groups' => [$groupId],
        ];
    }
}
