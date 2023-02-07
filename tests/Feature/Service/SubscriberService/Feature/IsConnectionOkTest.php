<?php

namespace Tests\Feature\Service\SubscriberService\Feature;

use App\Service\SubscriberService\SubscriberService;
use Tests\Feature\Service\SubscriberService\Traits\MailingListProviderTrait;
use Tests\TestCase;

class IsConnectionOkTest extends TestCase
{

    use MailingListProviderTrait;

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_api_key_is_valid_then_isConnectionOk_should_return_true($mailProvider)
    {
        // given
        $this->subscriberService = new SubscriberService($mailProvider());

        // then
        $this->assertTrue($this->subscriberService->isConnectionOk());
    }

    /**
     * @test
     * @dataProvider invalidMailProviders
     */
    public function when_api_key_is_invalid_then_isConnectionOk_should_return_false($invalidProviderType)
    {
        // given
        $this->subscriberService = new SubscriberService($invalidProviderType());

        // then
        $this->assertFalse($this->subscriberService->isConnectionOk());
    }

}
