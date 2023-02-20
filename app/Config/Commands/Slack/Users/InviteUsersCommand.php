<?php

use App\Commands\Slack\Users\InviteUserCommand;

return array(
    'InviteUsers' => [
        'class' => InviteUserCommand::class,
        'title' => [
            'en' => 'Invite Users',
            'pl' => 'Zaproś użytkowników',
        ],
        'description' => [
            'en' => 'Invite 1-30 users to a public or private channel',
            'pl' => 'Zaproś 1-30 użytkowników do kanału publicznego lub prywatnego',
        ],
        'params' => [
            'channelType' => [
                'required' => true,
                'type' => 'select',
                'title' => [
                    'en' => 'Channel type',
                    'pl' => 'Typ kanału',
                ],
                'description' => [
                    'en' => 'Select the channel where you want to invite selected users.',
                    'pl' => 'Wybierz typ kanału, do którego chcesz zaprosić wybranych użytkowników.',
                ],
                'options' => [
                    'public' => [
                        'en' => 'Public',
                        'pl' => 'Publiczny',
                    ],
                    'private' => [
                        'en' => 'Private',
                        'pl' => 'Prywatny',
                    ]
                ]
            ],
            'channelName' => [
                'title' => 'Channel name',
                'required' => true,
                'type' => 'string',
                'description' => 'The name of the channel to invite the user.'
            ],
            'status',
        ],
    ],
);
