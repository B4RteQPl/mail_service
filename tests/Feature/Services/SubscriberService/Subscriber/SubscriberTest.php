<?php

namespace Tests\Feature\Services\SubscriberService\Subscriber;

use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;
use App\Services\SubscriberManager\Subscriber\Subscriber;
use App\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManager;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function when_subscriber_is_created_then_email_method_returns_instance_of_email_and_return_property_email_string()
    {
        $email = 'example@email.com';
        $subscriber = new Subscriber(new Email($email));

        $this->assertEquals( $email, $subscriber->email()->get());
        $this->assertTrue($subscriber->email() instanceof Email);
        $this->assertTrue( $subscriber->email instanceof Email);
        $this->assertEquals($email, (string) $subscriber->email);
    }

    /**
     * @test
     */
    public function when_subscriber_with_required_fields_is_created_then_expect_default_values ()
    {
        // when
        $subscriber = new Subscriber(new Email('example@email.com'));

        // then

        $this->assertEmpty( $subscriber->firstName()->get());
        $this->assertTrue($subscriber->firstName() instanceof FirstName);
        $this->assertTrue( $subscriber->firstName instanceof FirstName);
        $this->assertEmpty((string) $subscriber->firstName);

        $this->assertEmpty($subscriber->lastName()->get());
        $this->assertTrue($subscriber->lastName() instanceof LastName);
        $this->assertTrue($subscriber->lastName instanceof LastName);
        $this->assertEmpty((string) $subscriber->lastName);

        $this->assertEmpty($subscriber->mailingLists()->get());
        $this->assertTrue($subscriber->mailingLists() instanceof SubscriberListManager);
        $this->assertTrue($subscriber->mailingLists instanceof SubscriberListManager);

        $this->assertEmpty($subscriber->channelLists()->get());
        $this->assertTrue($subscriber->channelLists() instanceof SubscriberListManager);
        $this->assertTrue($subscriber->channelLists instanceof SubscriberListManager);
    }

    /**
     * @test
     */
    public function when_status_is_set_to_verified_then_status_is_verified_returns_true ()
    {
        // when
        $subscriber = new Subscriber(new Email('example@email.com'));
        $this->assertTrue($subscriber->isStatusNotVerified());
        $this->assertFalse($subscriber->isStatusVerified());
        $this->assertFalse($subscriber->isStatusVerificationPending());

        $subscriber->setStatusVerified('id');

        // then
        $this->assertFalse($subscriber->isStatusNotVerified());
        $this->assertTrue($subscriber->isStatusVerified());
        $this->assertFalse($subscriber->isStatusVerificationPending());
    }


    /**
     * @test
     */
    public function when_status_is_set_to_verification_pending_then_status_is_verification_pending_returns_true()
    {
        // when
        $subscriber = new Subscriber(new Email('example@email.com'));

        $subscriber->setStatusVerificationPending();

        // then
        $this->assertFalse($subscriber->isStatusNotVerified());
        $this->assertFalse($subscriber->isStatusVerified());
        $this->assertTrue($subscriber->isStatusVerificationPending());
    }

    /**
     * @test
     */
    public function when_to_array_is_returned_then_format_is_correct()
    {
        // when
        $subscriber = new Subscriber(new Email('example@email.com'));

        // then
        $this->assertEquals([
            'email' => 'example@email.com',
            'firstName' => '',
            'lastName' => '',
            'mailingLists' => [],
            'channelLists' => [],
            'status' => Subscriber::STATUS_NOT_VERIFIED,
            'id' => null,
            'job' => null,
        ], $subscriber->toArray());

    }
}
