<?php

namespace Tests\Feature\Services\SubscriberService\Feature\MailProvider;

use App\Services\SubscriberManager\SubscriberServices\MailingService;
use Tests\Feature\Services\SubscriberService\Traits\MailDeliveryServiceProviderTrait;
use Tests\TestCase;

class IsConnectionOkTest extends TestCase
{

    use MailDeliveryServiceProviderTrait;

    /**
     * @test
     * @dataProvider validMailRequester
     */
    public function when_api_key_is_valid_then_is_connection_ok_should_return_true($deliveryService)
    {
        // given
        $deliveryService = new MailingService($deliveryService());

        // then
        $this->assertTrue($deliveryService->isConnectionOk());
    }

    /**
     * @test
     * @dataProvider invalidMailRequester
     */
    public function when_api_key_is_invalid_then_is_connection_ok_should_return_false($invalidDeliveryService)
    {
        // given
        $deliveryService = new MailingService($invalidDeliveryService());

        // then
        $this->assertFalse($deliveryService->isConnectionOk());
    }

}
