<?php

namespace Tests\Feature\Service\SubscriberService\Feature;

use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\SubscriberService;
use Tests\Feature\Service\SubscriberService\Traits\MailingListProviderTrait;
use Tests\TestCase;

class GetVerifiedSubscriberTest extends TestCase
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
    public function when_get_subscriber_draft_then_returns_subscriber_verified($mailProvider)
    {
        $this->assertTrue($this->subscriber->isDraft());

        $this->subscriberService = new SubscriberService($mailProvider());
        $subscriber = $this->addTestSubscriberToList($this->subscriber, $this->subscriberService);
//        $subscriber = $this->subscriberService->addSubscriber($this->subscriber);

        sleep(10);
        $this->assertTrue($this->subscriber->isDraft());
        $this->assertTrue($this->subscriberService->getVerifiedSubscriber($subscriber)->isVerified());
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_get_subscriber_verified_then_returns_subscriber_verified($mailProvider)
    {
        $this->subscriberService = new SubscriberService($mailProvider());

        // first add draft subscriber to verify
        $subscriberDraft = new SubscriberDraft($this->getUniqueEmail());
        $subscriberVerified = $this->addTestSubscriberToList($subscriberDraft, $this->subscriberService);

        $this->assertTrue($subscriberDraft->isDraft());
        $this->assertTrue($subscriberVerified->isVerified());

        $this->assertTrue($this->subscriberService->getVerifiedSubscriber($subscriberVerified)->isVerified());
    }
}
