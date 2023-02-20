<?php

namespace App\Services\ExternalServices\ActiveCampaign\Commands\ContactLists;

use App\Services\ExternalServices\ActiveCampaign\Commands\AbstractCommand;

class ActiveCampaignCommandUpdateListStatusForContact extends AbstractCommand
{
    public function execute(array $params): ?array
    {
        $listId = $params['listId'];
        $contactId = $params['contactId'];
        $status = $params['status'];

        return $this->client->updateListStatusForContact($listId, $contactId, $status);
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => 'Zmień status listy dla kontaktu',
                'en' => 'Update list status for contact',
            ],
            'description' => [
                'pl' => '',
                'en' => '',
            ],
            'parameters' => [
                'listId' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'ID listy',
                        'en' => 'List ID',
                    ],
                ],
                'contactId' => [
                    'type' => 'string',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'ID kontaktu',
                        'en' => 'Contact ID',
                    ],
                ],
                'status' => [
                    'type' => 'select',
                    'required' => true,
                    'placeholder' => [
                        'pl' => 'Status kontaktu',
                        'en' => 'Contact status',
                    ],
                    'options' => [
                        'pl' => [
                            '1' => 'Aktywny',
                            '2' => 'Nieaktywny',
                        ],
                        'en' => [
                            '1' => 'Active',
                            '2' => 'Inactive',
                        ]
                    ]
                ],
            ],
        ];
    }
}
