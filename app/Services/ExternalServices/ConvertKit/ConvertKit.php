<?php

namespace App\Services\ExternalServices\ConvertKit;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\ConvertKit\Client\ConvertKitClient;
use App\Services\ExternalServices\ConvertKit\Commands\Subscribers\ConvertKitCommandFindSubscriber;
use App\Services\ExternalServices\ConvertKit\Commands\SubscriberTags\ConvertKitCommandAddSubscriberToTag;
use App\Services\ExternalServices\ConvertKit\Commands\SubscriberTags\ConvertKitCommandRemoveTagFromSubscriber;
use App\Services\ExternalServices\ConvertKit\Commands\Tags\ConvertKitCommandListTags;

class ConvertKit extends AbstractCommandLoader
{
    protected ?ConvertKitClient $client = null;

    const ACTIVE_COMMANDS = [
        ConvertKitCommandListTags::class => 'listTags',
        ConvertKitCommandAddSubscriberToTag::class => 'addSubscriberToTag',
        ConvertKitCommandFindSubscriber::class => 'findSubscriber',
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
