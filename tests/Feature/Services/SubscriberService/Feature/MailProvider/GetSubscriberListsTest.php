<?php

namespace Tests\Feature\Services\SubscriberService\Feature\MailProvider;

use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;
use App\Services\SubscriberManager\SubscriberServices\MailingService;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\MailChimp\DeliveryService as MailchimpRequester;
use Tests\Feature\Services\SubscriberService\Traits\MailDeliveryServiceProviderTrait;
use Tests\TestCase;

class GetSubscriberListsTest extends TestCase
{

    use MailDeliveryServiceProviderTrait;

    /**
     * FOR MANUAL PURPOSES ONLY
     * e.g. Active Campaign and GetResponse are initialized with 1 list as default
     * @test
     * @dataProvider validMailRequester
     */
    public function when_account_has_no_groups_then_get_mailing_lists_returns_empty_array($deliveryService)
    {
        $this->markTestSkipped('Run only when testing account has no groups');

        // given
        $this->subscriberService = new MailingService($deliveryService());

        // when
        $mailingLists = $this->subscriberService->getSubscriberLists();

        // then
        $this->assertIsArray($mailingLists);
        $this->assertEmpty($mailingLists);
    }

    /**
     * FOR MANUAL PURPOSES ONLY
     *
     * @test
     * @dataProvider validMailRequester
     */
    public function when_get_mailing_list_then_return_array_of_mailing_list($deliveryService)
    {
        // $this->markTestSkipped('Run only when testing account has groups');

        // given
        $this->subscriberService = new MailingService($deliveryService());

        // when
        $mailingLists = $this->subscriberService->getSubscriberLists();

        // then
        $this->assertIsArray($mailingLists);
        $this->assertNotEmpty($mailingLists);
        $this->assertContainsOnlyInstancesOf(MailingList::class, $mailingLists);

        if ($deliveryService() instanceof MailchimpRequester) {
            $this->assertCount(1, $mailingLists);
        } else {
            $this->assertCount(2, $mailingLists);
        }
    }
}
