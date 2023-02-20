<?php

use App\Commands\Slack\Users\KickUserCommand;

return array(
    'KickUser' => [
        'class' => KickUserCommand::class,
        'title' => [
            'pl' => 'Wyrzuć użytkownika',
            'en' => 'Kick a User'
        ],
        'description' => [
            'pl' => 'Usuwa użytkownika z kanału',
            'en' => 'Removes user from channel'
        ],
        'parameters' => [],
    ]
);
