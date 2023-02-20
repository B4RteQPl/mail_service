<?php


use App\Commands\Slack\Users\GetUserCommand;

return array(
    'GetUser' => [
        'class' => GetUserCommand::class,
        'title' => [
            'pl' => 'Pobierz użytkownika',
            'en' => 'Get a User',
        ],
        'description' => [
            'pl' => 'Zwróć szczegóły o użytkowniku',
            'en' => 'Returns details about a member of a workspace',
        ],
        'parameters' => [],
    ],
);
