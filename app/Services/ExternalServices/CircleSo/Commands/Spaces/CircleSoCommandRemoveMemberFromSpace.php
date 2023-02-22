<?php


namespace App\Services\ExternalServices\CircleSo\Commands\Spaces;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandRemoveMemberFromSpace extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);
        $spaceId = $params['spaceId'];

        return $this->client->removeMemberFromSpace($communityId, $email, $spaceId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Usuń z przestrzeni',
                'en' => 'Remove from space',
            ],
            'fields' => [
                'spaceIds' => [
                    'type' => 'select',
                    'options' => $this->client->getSpaces(),
                    'placeholder' => [
                        'pl' => 'Wybierz przestrzeń',
                        'en' => 'Select space'
                    ],
                ],
            ],
        ];
    }
}
