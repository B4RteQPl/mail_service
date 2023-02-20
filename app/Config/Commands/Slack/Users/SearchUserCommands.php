<?php

use App\Commands\Slack\Users\SearchUserCommand;

return array(
    'SearchUser' => [
        'class' => SearchUserCommand::class,
        'title' => [
            'en' => 'Search for User',
            'pl' => 'Szukaj użytkownika',
        ],
        'description' => [
            'en' => 'Returns a list of all users in a workspace',
            'pl' => 'Zwraca listę wszystkich użytkowników w workspace',
        ],
    ],
);
