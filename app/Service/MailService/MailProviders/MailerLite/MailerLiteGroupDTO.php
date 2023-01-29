<?php

namespace App\Service\MailService\MailProviders\MailerLite;

class MailerLiteGroupDTO
{

    static function toArray (array $groups): array
    {
        $groupsDTO = [];

        foreach ($groups as $group) {
            $groupsDTO[] = [
                'id' => $group['id'],
                'name' => $group['name'],
            ];
        }

        return $groupsDTO;
    }
}
