<?php

namespace Tests\Feature\Services\SubscriberService\Feature\MailProvider;

use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;
use App\Services\SubscriberManager\SubscriberServices\MailingService;
use Tests\Feature\Services\SubscriberService\Traits\MailDeliveryServiceProviderTrait;
use Tests\Feature\Services\SubscriberService\Traits\SubscriberListProviderTrait;
use Tests\Feature\Services\SubscriberService\Traits\SubscriberProviderTrait;
use Tests\TestCase;

class AddSubscriberToSubscriberListTest extends TestCase
{

    use MailDeliveryServiceProviderTrait;
    use SubscriberProviderTrait;
    use SubscriberListProviderTrait;

    /**
     * @test
     * @dataProvider validMailRequester
     */
    public function when_subscriber_is_added_to_list_then_subscriber_has_new_mailing_list($deliveryService)
    {
        // given
        $mailingService = new MailingService($deliveryService());

        $subscriber = $this->getSubscriberWithFirstNameAndLastName();
        $id = $deliveryService->getTestingGroupId();
        $subscriberList = new MailingList($id, 'Test Group', $deliveryService->getType());

        // when
        dump($subscriber, $subscriberList);
        $updatedSubscriber = $mailingService->addSubscriberToSubscriberList($subscriber, $subscriberList);

        $subscriber = $this->getSubscriberWithRequiredFields();
        $this->assertTrue($subscriber->mailingLists()->isEmpty());

        $mailingService = new MailingService($deliveryService());
        $subscriberList = $mailingService->getSubscriberLists()[0];

        $mailingService->addSubscriberToSubscriberList($subscriber, $subscriberList);

        $this->assertFalse($subscriber->mailingLists()->isEmpty());
        $this->assertCount(1, $subscriber->mailingLists()->get());
        $this->assertTrue($subscriber->mailingLists()->has($subscriberList));
        dump($subscriber);
    }
}
