<?php


namespace App\Services\ExternalServices\CircleSo\Commands\SpaceGroups;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandAddMemberToSpaceGroup extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);
        $spaceGroupId = $params['spaceGroupId'];

        return $this->client->addMemberToSpaceGroup($communityId, $email, $spaceGroupId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dodaj kontakt do grupy przestrzeni',
                'en' => 'Add contact to space group',
            ],
            'fields' => [
                'communityId' => [
                    'type' => 'select',
                    'options' => $this->client->getCommunityList(),
                    'placeholder' => [
                        'pl' => 'Wybierz społeczność',
                        'en' => 'Select community'
                    ],
                ],
                'spaceGroupId' => [
                    'type' => 'select',
                    'options' => $this->client->getSpaceGroups(),
                    'placeholder' => [
                        'pl' => 'Wybierz grupę przestrzeni',
                        'en' => 'Select space group'
                    ],
                ],
            ],
        ];
    }
}
