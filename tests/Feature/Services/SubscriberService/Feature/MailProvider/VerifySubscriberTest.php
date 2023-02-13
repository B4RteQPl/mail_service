<?php

namespace Tests\Feature\Services\SubscriberService\Feature\MailProvider;

use App\Services\SubscriberManager\SubscriberServices\MailingService;
use Tests\Feature\Services\SubscriberService\Traits\MailDeliveryServiceProviderTrait;
use Tests\Feature\Services\SubscriberService\Traits\SubscriberProviderTrait;
use Tests\TestCase;

class VerifySubscriberTest extends TestCase
{

    use MailDeliveryServiceProviderTrait;
    use SubscriberProviderTrait;

    /**
     * @test
     * @dataProvider validMailRequester
     */
    public function when_get_verified_subscriber_then_subscriber_has_id_and_is_verified_returns_true($deliveryService)
    {
        $subscriber = $this->getSubscriberWithRequiredFields();

        $mailingService = new MailingService($deliveryService());
        $subscriberList = $mailingService->getSubscriberLists()[0];

        $mailingService->addSubscriberToSubscriberList($subscriber, $subscriberList);

        $this->assertTrue($subscriber->isStatusVerified() || $subscriber->isStatusVerificationPending());
        $this->assertTrue($subscriberList->isStatusVerified() || $subscriberList->isStatusVerificationPending());

        if ($subscriber->isStatusVerified()) {
            $this->assertTrue($subscriber->isStatusVerified());
            $this->assertNotEmpty($subscriber->id);

            return;
        }

        if ($subscriber->isStatusVerificationPending()) {
            $this->assertTrue($subscriber->isStatusVerificationPending());
            $this->assertEmpty($subscriber->id);
        }
    }
}
