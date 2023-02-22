<?php


namespace App\Services\ExternalServices\CircleSo\Commands\SpaceGroups;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandRemoveMemberFromSpaceGroups extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);
        $spaceGroupId = $params['spaceGroupId'];

        return $this->client->removeMemberFromSpaceGroup($communityId, $email, $spaceGroupId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Usuń kontakt z grupy przestrzeni',
                'en' => 'Remove contact from space group',
            ],
            'fields' => [
                'spaceGroupIds' => [
                    'type' => 'select',
                    'options' => $this->client->getSpaceGroups(),
                    'placeholder' => [
                        'pl' => 'Wybierz przestrzeń',
                        'en' => 'Select space group'
                    ],
                ],
            ],
        ];
    }
}
