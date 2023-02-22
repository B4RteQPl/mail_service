<?php


namespace App\Services\ExternalServices\CircleSo\Commands\Spaces;

use App\Services\ExternalServices\CircleSo\Commands\AbstractCommand;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

class CircleSoCommandAddMemberToSpace extends AbstractCommand
{
    public function execute(array $params)
    {
        $communityId = $params['communityId'];
        $email = new Email($params['email']);
        $spaceId = $params['spaceId'];

        return $this->client->addMemberToSpace($communityId, $email, $spaceId);
    }

    public function getConfig()
    {
        return [
            'actionName' => [
                'pl' => 'Dodaj kontakt do kanału',
                'en' => 'Add contact to space',
            ],
            'fields' => [
                'spaceId' => [
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
