<?php

namespace App\Services\ExternalServices\ConvertKit;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\ConvertKit\Client\ConvertKitClient;
use App\Services\ExternalServices\ConvertKit\Commands\SubscriberTags\ConvertKitCommandAddSubscriberToTag;
use App\Services\ExternalServices\ConvertKit\Commands\SubscriberTags\ConvertKitCommandRemoveTagFromSubscriber;

class ConvertKit extends AbstractCommandLoader
{
    protected ?ConvertKitClient $client = null;

    const ACTIVE_COMMANDS = [
        ConvertKitCommandAddSubscriberToTag::class => 'addSubscriberToTag',
        ConvertKitCommandRemoveTagFromSubscriber::class => 'removeTagFromSubscriber',
    ];

    public function __construct(?ConvertKitClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiSecret): ConvertKit
    {
        $client = new ConvertKitClient($apiSecret);

        return new ConvertKit($client);
    }
}
