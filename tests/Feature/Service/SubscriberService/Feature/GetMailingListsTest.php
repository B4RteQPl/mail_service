<?php

namespace Tests\Feature\Service\SubscriberService\Feature;

use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\SubscriberService;
use Tests\Feature\Service\SubscriberService\Traits\MailingListProviderTrait;
use Tests\TestCase;

class GetMailingListsTest extends TestCase
{

    use MailingListProviderTrait;

    /**
     * FOR MANUAL PURPOSES ONLY
     * e.g. Active Campaign and GetResponse are initialized with 1 list as default
     * @test
     * @dataProvider validMailProviders
     */
    public function when_account_has_no_groups_then_get_mailing_lists_returns_empty_array($mailProvider)
    {
        $this->markTestSkipped('Run only when testing account has no groups');

        // given
        $this->subscriberService = new SubscriberService($mailProvider());

        // when
        $mailingLists = $this->subscriberService->getMailingLists();

        // then
        $this->assertIsArray($mailingLists);
        $this->assertEmpty($mailingLists);
    }

    /**
     * FOR MANUAL PURPOSES ONLY
     *
     * @test
     * @dataProvider validMailProviders
     */
    public function when_get_mailing_list_then_return_array_of_mailing_list($mailProvider)
    {
        // $this->markTestSkipped('Run only when testing account has groups');

        // given
        $this->subscriberService = new SubscriberService($mailProvider());

        // when
        $mailingLists = $this->subscriberService->getMailingLists();

        // then
        $this->assertIsArray($mailingLists);
        $this->assertNotEmpty($mailingLists);
        $this->assertCount(2, $mailingLists);
        $this->assertContainsOnlyInstancesOf(MailingList::class, $mailingLists);
    }
}
