<?php


namespace App\Services\ExternalServices\CircleSo\Commands\Members;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandInviteMember extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);
        $firstname = new FirstName($params['firstName']);
        $lastname = new LastName($params['lastName']);
        $spaceIds = $params['spaceIds'];
        $spaceGroupIds = $params['spaceGroupIds'];

        return $this->client->inviteMember($communityId, $email, $firstname, $lastname, $spaceIds, $spaceGroupIds);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Zaproś do społeczności',
                'en' => 'Invite to community',
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
                'spaceGroupIds' => [
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
