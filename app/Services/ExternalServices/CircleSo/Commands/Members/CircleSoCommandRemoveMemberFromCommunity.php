<?php


namespace App\Services\ExternalServices\CircleSo\Commands\Members;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandRemoveMemberFromCommunity extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);

        return $this->client->removeMemberFromCommunity($communityId, $email);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Usuń kontakt z społeczności',
                'en' => 'Remove contact from community',
            ],
        ];
    }
}
