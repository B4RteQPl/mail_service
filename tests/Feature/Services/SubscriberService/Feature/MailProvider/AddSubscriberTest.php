<?php

namespace Tests\Feature\Services\SubscriberService\Feature\MailProvider;

use App\Exceptions\Service\SubscriberService\SubscriberAddingIsNotSupportedException;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\ConvertKit\DeliveryService as ConvertKitRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\GetResponse\DeliveryService as GetResponseRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\Mailchimp\DeliveryService as MailchimpRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingService;
use Tests\Feature\Services\SubscriberService\Traits\MailDeliveryServiceProviderTrait;
use Tests\Feature\Services\SubscriberService\Traits\SubscriberProviderTrait;
use Tests\TestCase;

class AddSubscriberTest extends TestCase
{

    use MailDeliveryServiceProviderTrait;
    use SubscriberProviderTrait;

    /**
     * @test
     * @dataProvider validMailRequester
     */
    public function when_subscriber_is_successfully_added_then_returns_subscriber_with_status_verified_or_verification_pending($deliveryService)
    {
        $subscriber = $this->getSubscriberWithRequiredFields();

        $mailingService = new MailingService($deliveryService());
        $this->assertTrue($subscriber->isStatusNotVerified());
        $this->assertEmpty($subscriber->id);

        if ($deliveryService() instanceof ConvertKitRequester) {
            $this->expectException(SubscriberAddingIsNotSupportedException::class);
            $this->expectExceptionMessage('ConvertKit requires tags to add subscriber');

            $mailingService->addSubscriber($subscriber);
            return;
        }

        if ($deliveryService() instanceof GetResponseRequester) {
            $this->expectException(SubscriberAddingIsNotSupportedException::class);
            $this->expectExceptionMessage('GetResponse requires campaign to add subscriber');

            $mailingService->addSubscriber($subscriber);
            return;
        }

        if ($deliveryService() instanceof MailchimpRequester) {
            $this->expectException(SubscriberAddingIsNotSupportedException::class);
            $this->expectExceptionMessage('Mailchimp requires list id to add subscriber');

            $mailingService->addSubscriber($subscriber);
            return;
        }

        $addedSubscriber = $mailingService->addSubscriber($subscriber);

        if ($addedSubscriber->isStatusVerified()) {
            $this->assertNotEmpty($addedSubscriber->id);
            $this->assertTrue($addedSubscriber->isStatusVerified());
            return;
        }

        if ($addedSubscriber->isStatusVerificationPending()) {
            $this->assertEmpty($addedSubscriber->id);
            $this->assertNotEmpty($addedSubscriber->job);
            $this->assertTrue($addedSubscriber->isStatusVerificationPending());
        }
    }
}
