<?php

namespace App\Services\ExternalServices\ConvertKit\Commands\Tags;

use App\Services\ExternalServices\ConvertKit\Commands\AbstractCommand;

/**
 * @url https://developers.activecampaign.com/reference/create-a-new-contact
 */
class ConvertKitCommandListTags extends AbstractCommand
{
    public function execute()
    {
        return $this->client->listTags();
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => '',
                'en' => '',
            ],
            'description' => [
                'pl' => '',
                'en' => '',
            ],
            'parameters' => [

            ],
        ];
    }
}
