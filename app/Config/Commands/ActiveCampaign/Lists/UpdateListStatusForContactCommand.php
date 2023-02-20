<?php


use App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists\ActiveCampaignCommandUpdateListStatusForContact;

return array(
    'class' => ActiveCampaignCommandUpdateListStatusForContact::class,
    'title' => [
        'pl' => 'Pobierz użytkownika',
        'en' => 'Get a User',
    ],
    'description' => [
        'pl' => 'Zwróć szczegóły o użytkowniku',
        'en' => 'Returns details about a member of a workspace',
    ],
    'parameters' => [],
);
