<?php

namespace Tests\Feature\Services\SubscriberService\Traits;

use App\Services\SubscriberManager\SubscriberServices\ChannelServices\CircleSo\DeliveryService as CircleSoDeliveryService;
use App\Services\SubscriberManager\SubscriberServices\ChannelServices\Discord\DeliveryService as DiscordDeliveryService;
use App\Services\SubscriberManager\SubscriberServices\ChannelServices\Slack\DeliveryService as SlackDeliveryService;

trait ChannelProviderTrait
{

    /**
     * @return array[]
     */
    public function validChannelProviders(): array
    {
        return [
            'Circle.so' => [function () { return new CircleSoDeliveryService(env('TEST_CIRCLE_SO_API_KEY')); }],
            'Slack' => [function () { return new SlackDeliveryService(env('TEST_SLACK_API_KEY')); }],
            'Discord' => [function () { return new DiscordDeliveryService(env('TEST_DISCORD_API_KEY')); }],
        ];
    }

    /**=
     * @return array[]
     */
    public function invalidChannelProviders(): array
    {
        return [
            'Circle.so' => [function () { return new CircleSoDeliveryService('invalid'); }],
            'Slack' => [function () { return new SlackDeliveryService('invalid'); }],
            'Discord' => [function () { return new DiscordDeliveryService('invalid'); }],
        ];
    }

}
