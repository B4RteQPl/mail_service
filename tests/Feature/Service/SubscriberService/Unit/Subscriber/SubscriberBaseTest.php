<?php

namespace Tests\Feature\Service\SubscriberService\Unit\Subscriber;

use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberBase;
use PHPUnit\Framework\TestCase;

class SubscriberBaseTest extends TestCase
{
    /**
     * @test
     */
    public function when_subscriber_base_is_created_with_empty_email_then_throw_exception()
    {
        // when
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Email is invalid');

        // then
        new SubscriberBase('');
    }

    /**
     * @test
     */
    public function when_subscriber_base_is_created_using_invalid_email_then_exception_is_thrown()
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Email is invalid');

        // when
        new SubscriberBase('invalid_email');
    }

    /**
     * @test
     */
    public function when_subscriber_base_email_is_set_to_invalid_then_exception_is_thrown ()
    {
        // given
        $subscriber = new SubscriberBase('example@email.com');

        // expect
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Email is invalid');

        // when
        $subscriber->setEmail('invalid_email');
    }

    /**
     * @test
     */
    public function when_empty_subscriber_is_created_then_string_properties_are_equal_null ()
    {
        // given
        $email = 'example@email.com';

        // when
        $subscriber = new SubscriberBase($email);

        // then
        $this->assertEquals($email, $subscriber->getEmail());

        $this->assertNull( $subscriber->getFirstName());
        $this->assertNull($subscriber->getLastName());
//        $this->assertNull($subscriber->getId());
    }

    /**
     * @test
     */
    public function when_empty_subscriber_is_created_then_subscriber_groups_is_empty_array()
    {
        // when
        $subscriber = new SubscriberBase('example@email.com');

        // then
        $this->assertEmpty($subscriber->getMailingLists());
    }

    //    todo add id test
    /**
     * @test
     */
    public function when_subscriber_base_is_created_then_subscriber_properties_can_be_set_using_setters ()
    {
        // given
        $email = 'example@email.com';
        $firstName = 'John';
        $lastName = 'Doe';

        // when
        $subscriber = new SubscriberBase($email);
        $subscriber->setFirstName($firstName);
        $subscriber->setLastName($lastName);

        // then
        $this->assertEquals($email, $subscriber->getEmail());
        $this->assertEquals($firstName, $subscriber->getFirstName());
        $this->assertEquals($lastName, $subscriber->getLastName());
    }

    /**
     * @test
     */
    public function when_subscriber_base_is_created_then_subscriber_mailing_lists_properties_can_be_set_using_setters ()
    {
        // given
        $mailingList = new MailingList('id', 'name', 'type');
        $subscriber = new SubscriberBase('example@email.com');
        $this->assertEquals([], $subscriber->getMailingLists());

        // when
        $subscriber->setMailingLists([$mailingList]);

        // then
        $this->assertEquals([$mailingList], $subscriber->getMailingLists());
    }

    /**
     * @test
     */
    public function when_subscriber_is_created_then_subscriber_and_subscriber_mailing_list_properties_can_be_set_using_constructor ()
    {
        // given
        $email = 'example@email.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $mailingList = new MailingList('id', 'name', 'type');

        // when
        $subscriber = new SubscriberBase($email, $firstName, $lastName, [$mailingList]);

        // then
        $this->assertEquals($email, $subscriber->getEmail());
        $this->assertEquals($firstName, $subscriber->getFirstName());
        $this->assertEquals($lastName, $subscriber->getLastName());
        $this->assertEquals([$mailingList], $subscriber->getMailingLists());
    }

    /**
     * @test
     */
    public function when_mailing_lists_is_set_using_array_of_not_mailing_lists_then_throw_exception ()
    {
        // given
        $subscriber = new SubscriberBase('example@email.com');

        // expect
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('mailingLists array stores only MailingList types');

        // when
        $subscriber->setMailingLists(['invalid_type']);
    }

    /**
     * @test
     */
    public function when_subscriber_has_added_and_deleted_mailing_lists_then_mailing_list_is_updated_correctly()
    {
        $subscriber = new SubscriberBase('example@email.com');
        $this->assertCount(0, $subscriber->getMailingLists());

        $mailingList = new MailingList('id', 'name', 'type');
        $nextMailingList = new MailingList('next id', 'next name', 'next type');

        $subscriber->addMailingList($mailingList);
        $subscriber->addMailingList($nextMailingList);

        $this->assertCount(2, $subscriber->getMailingLists());
        $this->assertEquals([$mailingList, $nextMailingList], $subscriber->getMailingLists());

        $subscriber->deleteMailingList($nextMailingList);

        $this->assertCount(1, $subscriber->getMailingLists());
        $this->assertEquals([$mailingList], $subscriber->getMailingLists());

        $subscriber->deleteMailingList($mailingList);

        $this->assertCount(0, $subscriber->getMailingLists());
        $this->assertEquals([], $subscriber->getMailingLists());
    }
}
