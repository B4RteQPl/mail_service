<?php

namespace Tests\Feature\Service\SubscriberService\Unit\Subscriber;

use App\Service\SubscriberService\Subscriber\SubscriberBase;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;
use PHPUnit\Framework\TestCase;

class SubscriberDraftTest extends TestCase
{

    /**
     * @test
     */
    public function subscriber_draft_extends_subscriber_base() {

        // given
        $subscriberDraft = new SubscriberDraft('example@example.com');

        // then
        $this->assertInstanceOf(SubscriberBase::class, $subscriberDraft);
        $this->assertInstanceOf(SubscriberDraft::class, $subscriberDraft);
        $this->assertNotInstanceOf(SubscriberVerified::class, $subscriberDraft);
    }

    /**
     * @test
     */
    public function subscriber_draft_doesnt_have_get_id_and_set_id_methods()
    {
        // given
        $subscriberDraft = new SubscriberDraft('example@example.com');

        $this->assertFalse(method_exists($subscriberDraft, 'setId'));
        $this->assertFalse(method_exists($subscriberDraft, 'setId'));
    }

    /**
     * @test
     */
    public function subscriber_draft_has_get_subscriber_verified_method()
    {
        $subscriberDraft = new SubscriberDraft('example@example.com');

        $this->assertInstanceOf(SubscriberDraft::class, $subscriberDraft);
        $this->assertNotInstanceOf(SubscriberVerified::class, $subscriberDraft);

        $subscriberVerified = $subscriberDraft->getSubscriberVerified('id');

        $this->assertInstanceOf(SubscriberVerified::class, $subscriberVerified);
        $this->assertNotInstanceOf(SubscriberDraft::class, $subscriberVerified);
    }

    /**
     * @test
     */
    public function when_subscriber_draft_then_is_draft_returns_true()
    {
        $subscriberDraft = new SubscriberDraft('example@example.com');

        $this->assertTrue($subscriberDraft->isDraft());
    }

    /**
     * @test
     */
    public function when_subscriber_draft_then_is_verified_returns_false()
    {
        $subscriberDraft = new SubscriberDraft('example@example.com');

        $this->assertFalse($subscriberDraft->isVerified());
    }
}
