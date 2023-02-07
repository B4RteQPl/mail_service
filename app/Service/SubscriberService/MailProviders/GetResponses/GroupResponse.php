<?php

namespace App\Service\SubscriberService\MailProviders\GetResponses;

use App\Interfaces\GroupResponseInterface;

class GroupResponse implements GroupResponseInterface
{

    static function convert ($response): array
    {
        $groups = $response;

        $DTO = [];

        foreach ($groups as $group) {
            $DTO[] = [
                'id' => $group['campaignId'],
                'name' => $group['name'],
            ];
        }
        return $DTO;
    }
}
