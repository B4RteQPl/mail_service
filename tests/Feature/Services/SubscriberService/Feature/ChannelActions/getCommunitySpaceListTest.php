<?php

namespace Tests\Feature\Services\SubscriberService\Feature\ChannelActions;

use App\Services\SubscriberManager\Subscriber\Subscriber;
use App\Services\SubscriberManager\SubscriberServices\ChannelService;
use App\ValueObjects\Email;
use Tests\Feature\Services\SubscriberService\Traits\ChannelProviderTrait;
use Tests\Feature\Services\SubscriberService\Traits\SubscriberProviderTrait;
use Tests\TestCase;

class getCommunitySpaceListTest extends TestCase
{

    use ChannelProviderTrait;
    use SubscriberProviderTrait;

    /**
     * @test
     * @dataProvider validChannelProviders
     */
    public function when_api_key_is_valid_then_is_connection_ok_should_return_true($deliveryService)
    {
        // given
        $deliveryService = new ChannelService($deliveryService());

        // then
        $communityList = $deliveryService->getCommunityList();
//        dump($communityList[0]->id);

        $subscriber = new Subscriber(new Email('bmaciejewicz@aol.com'));
//        $subscriber = $this->getSubscriberWithRequiredFields();
        $communitySpaceList = $deliveryService->getCommunitySpaceList($communityList[0])[0];
        dump($communitySpaceList);

        dump($deliveryService->addSubscriberToCommunitySpaceList($subscriber, $communitySpaceList));
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
