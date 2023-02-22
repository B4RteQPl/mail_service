<?php

namespace Tests\Feature\Services\SubscriberService\SubscriberList;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManager;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;
use Tests\TestCase;

class SubscriberListManagerTest extends TestCase
{

    protected SubscriberListInterface $subscriberListMock;

    public function setUp(): void {
        parent::setUp();

        $this->subscriberList = new MailingList('id', 'name', "type");
        $this->nextSubscriberList = new MailingList('next_id', 'next_name', "next_type");   }

    /**
     * @test
     */
    public function when_subscriber_list_manager_is_created_then_list_is_empty()
    {
        $subscriberListManager = new SubscriberListManager();

        $this->assertEmpty($subscriberListManager->get());
        $this->assertTrue($subscriberListManager->isEmpty());
        $this->assertFalse($subscriberListManager->has($this->subscriberList));
        $this->assertFalse($subscriberListManager->has($this->nextSubscriberList));
    }

    /**
     * @test
     */
    public function when_list_implements_subscriber_list_interface_then_can_be_added_to_list()
    {
        // when
        $subscriberListManager = new SubscriberListManager();
        $subscriberListManager->add($this->subscriberList);

        // then
        $this->assertCount( 1, $subscriberListManager->get());
        $this->assertNotEmpty($subscriberListManager->get());
        $this->assertFalse($subscriberListManager->isEmpty());
        $this->assertTrue($subscriberListManager->has($this->subscriberList));
        $this->assertFalse($subscriberListManager->has($this->nextSubscriberList));
    }

    /**
     * @test
     */
    public function when_multiple_subscriber_list_are_added_then_can_be_deleted_from_list_correctly()
    {
        // when
        $subscriberListManager = new SubscriberListManager();
        $this->assertTrue($subscriberListManager->isEmpty());
        $this->assertEmpty($subscriberListManager->get());

        $subscriberListManager->add($this->subscriberList);
        $subscriberListManager->add($this->nextSubscriberList);

        $this->assertCount( 2, $subscriberListManager->get());
        $this->assertNotEmpty($subscriberListManager->get());
        $this->assertTrue($subscriberListManager->has($this->subscriberList));
        $this->assertTrue($subscriberListManager->has($this->nextSubscriberList));
        $this->assertFalse($subscriberListManager->isEmpty());

        $subscriberListManager->delete($this->subscriberList);
        $this->assertCount( 1, $subscriberListManager->get());
        $this->assertNotEmpty($subscriberListManager->get());
        $this->assertFalse($subscriberListManager->has($this->subscriberList));
        $this->assertTrue($subscriberListManager->has($this->nextSubscriberList));
        $this->assertFalse($subscriberListManager->isEmpty());

        // then
        $subscriberListManager->delete($this->nextSubscriberList);
        $this->assertCount( 0, $subscriberListManager->get());
        $this->assertEmpty($subscriberListManager->get());
        $this->assertTrue($subscriberListManager->isEmpty());
        $this->assertFalse($subscriberListManager->has($this->subscriberList));
        $this->assertFalse($subscriberListManager->has($this->nextSubscriberList));
    }

    /**
     * @test
     */
    public function when_same_multiple_subscriber_list_are_added_then_list_items_are_not_duplicated()
    {
        // given
        $subscriberListManager = new SubscriberListManager();

        $subscriberListManager->add($this->subscriberList);
        $this->assertCount(1, $subscriberListManager->get());

        $subscriberListManager->add($this->subscriberList);
        $this->assertCount(1, $subscriberListManager->get());
    }
}
