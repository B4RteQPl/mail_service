<?php

namespace Tests\Feature\Service\SubscriberService\Unit\Subscriber;

use App\Service\SubscriberService\Subscriber\SubscriberBase;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;
use PHPUnit\Framework\TestCase;

class SubscriberVerifiedTest extends TestCase
{

    /**
     * @test
     */
    public function subscriber_verified_extends_subscriber_base()
    {
        // given
        $subscriberVerified = new SubscriberVerified('id', 'example@example.com');

        // then
        $this->assertInstanceOf(SubscriberBase::class, $subscriberVerified);
        $this->assertInstanceOf(SubscriberVerified::class, $subscriberVerified);
        $this->assertNotInstanceOf(SubscriberDraft::class, $subscriberVerified);
    }

    /**
     * @test
     */
    public function subscriber_verified_has_method_to_get_id()
    {
        // given
        $id = 'id';

        // when
        $subscriberVerified = new SubscriberVerified($id, 'example@example.com');

        // then
        $this->assertEquals($id, $subscriberVerified->getId());
    }

    /**
     * @test
     */
    public function subscriber_verified_has_method_to_set_id()
    {
        // given
        $subscriberVerified = new SubscriberVerified('id', 'example@example.com');

        // when
        $id = 'new_id';
        $subscriberVerified->setId($id);

        // then
        $this->assertEquals($id, $subscriberVerified->getId());
    }

    /**
     * @test
     */
    public function when_subscriber_verified_then_is_draft_returns_false()
    {
        $subscriberVerified = new SubscriberVerified('id', 'example@example.com');

        $this->assertFalse($subscriberVerified->isDraft());
    }

    /**
     * @test
     */
    public function when_subscriber_verified_then_is_verified_returns_true()
    {
        $subscriberVerified = new SubscriberVerified('id', 'example@example.com');

        $this->assertTrue($subscriberVerified->isVerified());
    }
}
