<?php

use App\Commands\Slack\Users\ListUsersCommand;

return array(
    'ListUsers' => [
        'class' => ListUsersCommand::class,
        'title' => [
            'pl' => 'Lista użytkowników',
            'en' => 'List Users',
        ],
        'description' => [
            'en' => 'Returns a list of all users in a workspace',
            'pl' => 'Zwraca listę wszystkich użytkowników w workspace',
        ],
        'parameters' => [],
    ],
);
