<?php

namespace Tests\Feature\Services\SubscriberService\Feature\MailProvider;

use App\Services\SubscriberManager\SubscriberServices\MailingService;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\GetResponse\DeliveryService as GetResponseRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\MailChimp\DeliveryService as MailchimpRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\Sendgrid\DeliveryService as SendgridRequester;
use Tests\Feature\Services\SubscriberService\Traits\MailDeliveryServiceProviderTrait;
use Tests\Feature\Services\SubscriberService\Traits\SubscriberProviderTrait;
use Tests\TestCase;

class DeleteSubscriberFromSubscriberListTest extends TestCase
{

    use MailDeliveryServiceProviderTrait;
    use SubscriberProviderTrait;

    /**
     * @test
     * @dataProvider validMailRequester
     */
    public function when_subscriber_has_no_subscriber_list_then_after_delete_subscriber_from_mailing_list_subscriber_is_not_changed_empty($deliveryService)
    {
        if ($deliveryService() instanceof MailchimpRequester) {
            $this->markTestSkipped('Mailchimp does not support multiple lists in free plan see test below');
        }

        if ($deliveryService() instanceof GetResponseRequester || $deliveryService() instanceof SendgridRequester) {
            $this->markTestSkipped('GetResponse and Sendgrid request is processed asynchronously, please check manual test with waiting time delay');
        }

        // given
        $subscriber = $this->getSubscriberWithRequiredFields();
        $this->assertTrue($subscriber->mailingLists()->isEmpty());

        $mailingService = new MailingService($deliveryService());

        $subscriberList = $mailingService->getSubscriberLists()[0];
        $nextSubscriberList = $mailingService->getSubscriberLists()[1];

        $mailingService->addSubscriberToSubscriberList($subscriber, $subscriberList);
        $this->assertFalse($subscriber->mailingLists()->isEmpty());
        $this->assertTrue($subscriber->mailingLists()->has($subscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($nextSubscriberList));
        $this->assertCount(1, $subscriber->mailingLists()->get());

        $mailingService->addSubscriberToSubscriberList($subscriber, $nextSubscriberList);
        $this->assertFalse($subscriber->mailingLists()->isEmpty());
        $this->assertTrue($subscriber->mailingLists()->has($nextSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($subscriberList));
        $this->assertCount(2, $subscriber->mailingLists()->get());

        // now when subscriberLists are added and confirmed, delete it
        $mailingService->deleteSubscriberFromSubscriberList($subscriber, $subscriberList);
        $this->assertFalse($subscriber->mailingLists()->isEmpty());
        $this->assertFalse($subscriber->mailingLists()->has($subscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($nextSubscriberList));
        $this->assertCount(1, $subscriber->mailingLists()->get());

        $mailingService->deleteSubscriberFromSubscriberList($subscriber, $nextSubscriberList);
        $this->assertTrue($subscriber->mailingLists()->isEmpty());
        $this->assertFalse($subscriber->mailingLists()->has($subscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($nextSubscriberList));
        $this->assertCount(0, $subscriber->mailingLists()->get());
    }

    /**
     * @test
     * @dataProvider validMailRequester
     */
    public function when_subscriber_is_assigned_to_list_then_remove_works_well($deliveryService)
    {
        if (!$deliveryService() instanceof MailchimpRequester) {
            $this->markTestSkipped('Only Mailchimp test case');
        }

        // given
        $subscriber = $this->getSubscriberWithRequiredFields();
        $this->assertTrue($subscriber->mailingLists()->isEmpty());

        $mailingService = new MailingService($deliveryService());

        $subscriberList = $mailingService->getSubscriberLists()[0];

        $mailingService->addSubscriberToSubscriberList($subscriber, $subscriberList);
        $this->assertFalse($subscriber->mailingLists()->isEmpty());
        $this->assertTrue($subscriber->mailingLists()->has($subscriberList));
        $this->assertCount(1, $subscriber->mailingLists()->get());

        // now when subscriberLists are added and confirmed, delete it
        $mailingService->deleteSubscriberFromSubscriberList($subscriber, $subscriberList);
        $this->assertTrue($subscriber->mailingLists()->isEmpty());
        $this->assertFalse($subscriber->mailingLists()->has($subscriberList));
        $this->assertCount(0, $subscriber->mailingLists()->get());
    }

    /**
     * !IMPORTANT - MANUAL TEST BECAUSE TAKES FEW MINUTES
     * @test
     */
    public function only_manual_test_when_subscriber_has_no_mailing_list_then_after_delete_subscriber_from_mailing_list_subscriber_is_not_changed_empty()
    {
        $this->markTestSkipped('Only manual test, because of time consuming');

        $getResponseDeliveryService = new GetResponseRequester(env('TEST_GETRESPONSE_API_KEY'));
        $getResponseMailingService = new MailingService($getResponseDeliveryService);
        $getResponseSubscriberList = $getResponseDeliveryService->getSubscriberLists()[0];
        $getResponseNextSubscriberList = $getResponseDeliveryService->getSubscriberLists()[1];

        $sendgridDeliveryService = new SendgridRequester(env('TEST_SENDGRID_API_KEY'));
        $sendgridMailingService = new MailingService($sendgridDeliveryService);
        $sendgridSubscriberList = $sendgridDeliveryService->getSubscriberLists()[0];
        $sendgridNextSubscriberList = $sendgridDeliveryService->getSubscriberLists()[1];

        // given
        $subscriber = $this->getSubscriberWithRequiredFields();
        $this->assertTrue($subscriber->mailingLists()->isEmpty());

        // tricky but works :D
        // call get response and sendgrid to add subscriber and wait one time

        dump(1);
        dump($subscriber);
        $this->assertFalse($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($sendgridNextSubscriberList));

        dump(2);
        dump($subscriber);
        $getResponseMailingService->addSubscriberToSubscriberList($subscriber, $getResponseSubscriberList);
        $this->assertFalse($subscriber->mailingLists()->isEmpty());
        $this->assertTrue($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertCount(1, $subscriber->mailingLists()->get());

        dump(3);
        dump($subscriber);
        $sendgridMailingService->addSubscriberToSubscriberList($subscriber, $sendgridSubscriberList);
        $this->assertTrue($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertCount(2, $subscriber->mailingLists()->get());

        sleep(1);
        dump(4);
        dump($subscriber);
        $getResponseMailingService->addSubscriberToSubscriberList($subscriber, $getResponseNextSubscriberList);
        $this->assertTrue($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertCount(3, $subscriber->mailingLists()->get());

        dump(5);
        dump($subscriber);
        $sendgridMailingService->addSubscriberToSubscriberList($subscriber, $sendgridNextSubscriberList);
        $this->assertTrue($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($sendgridNextSubscriberList));
        $this->assertCount(4, $subscriber->mailingLists()->get());

        sleep(600);
        dump(6);
        dump($subscriber);
        // now when subscriberLists are added and confirmed, delete it
        $sendgridMailingService->deleteSubscriberFromSubscriberList($subscriber, $sendgridSubscriberList);
        $this->assertFalse($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($sendgridNextSubscriberList));
        $this->assertCount(3, $subscriber->mailingLists()->get());

        sleep(10);
        dump(7);
        dump($subscriber);
        $getResponseMailingService->deleteSubscriberFromSubscriberList($subscriber, $getResponseSubscriberList);
        $this->assertFalse($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($sendgridNextSubscriberList));
        $this->assertCount(2, $subscriber->mailingLists()->get());

        sleep(10);
        dump(8);
        dump($subscriber);
        $getResponseMailingService->deleteSubscriberFromSubscriberList($subscriber, $getResponseNextSubscriberList);
        $this->assertFalse($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertTrue($subscriber->mailingLists()->has($sendgridNextSubscriberList));
        $this->assertCount(1, $subscriber->mailingLists()->get());

        sleep(10);
        dump(9);
        dump($subscriber);
        $sendgridMailingService->deleteSubscriberFromSubscriberList($subscriber, $sendgridNextSubscriberList);
        $this->assertTrue($subscriber->mailingLists()->isEmpty());
        $this->assertFalse($subscriber->mailingLists()->has($sendgridSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($getResponseSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($getResponseNextSubscriberList));
        $this->assertFalse($subscriber->mailingLists()->has($sendgridNextSubscriberList));
        $this->assertCount(0, $subscriber->mailingLists()->get());

        dump('end');
        dump($subscriber);
    }

    /**
     * @test
     */
    public function when_subscriber_is_deleted_manually_from_subscriber_list_then ()
    {
        $this->markTestSkipped('to clarify');
    }

    /**
     * @test
     */
    public function when_subscriber_list_is_deleted_from_account_manually_then ()
    {
        $this->markTestSkipped('to clarify');
    }

}
