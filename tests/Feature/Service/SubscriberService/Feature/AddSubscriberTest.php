<?php

namespace Tests\Feature\Service\SubscriberService\Feature;

use App\Service\SubscriberService\MailProviders\ConvertKit\ConvertKitMailProvider;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\SubscriberService;
use Tests\Feature\Service\SubscriberService\Traits\MailingListProviderTrait;
use Tests\TestCase;

class AddSubscriberTest extends TestCase
{

    use MailingListProviderTrait;

    protected SubscriberDraft $subscriber;

    public function setUp(): void {
        parent::setUp();
        $this->subscriber = new SubscriberDraft($this->getUniqueEmail(), 'Example first name', 'Example last name');
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_subscriber_draft_is_added_then_returns_subscriber_verified($mailProvider)
    {
        if ($mailProvider() instanceof ConvertKitMailProvider) {
            $this->markTestSkipped('ConvertKit does not support adding subscribers drafts without tags');
        }
        $this->assertTrue($this->subscriber->isDraft());

        $this->subscriberService = new SubscriberService($mailProvider());
        $subscriberVerified = $this->subscriberService->addSubscriber($this->subscriber);

        $this->assertTrue($subscriberVerified->isVerified());
    }
}
