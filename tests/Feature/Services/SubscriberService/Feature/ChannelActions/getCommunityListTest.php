<?php

namespace Tests\Feature\Services\SubscriberService\Feature\ChannelActions;

use App\Services\SubscriberManager\SubscriberServices\ChannelService;
use Tests\Feature\Services\SubscriberService\Traits\ChannelProviderTrait;
use Tests\TestCase;

class getCommunityListTest extends TestCase
{

    use ChannelProviderTrait;

    /**
     * @test
     * @dataProvider validChannelProviders
     */
    public function when_api_key_is_valid_then_is_connection_ok_should_return_true($deliveryService)
    {
        // given
        $deliveryService = new ChannelService($deliveryService());

        // then
        dump($deliveryService->getCommunityList());
//        $this->assertTrue($deliveryService->getCommunities());
    }

    /**
     * @test
     * @dataProvider invalidChannelProviders
     */
    public function when_api_key_is_invalid_then_is_connection_ok_should_return_false($invalidDeliveryService)
    {
        // given
        $deliveryService = new ChannelService($invalidDeliveryService());

        // then
        $this->assertFalse($deliveryService->getCommunities());
    }

}
