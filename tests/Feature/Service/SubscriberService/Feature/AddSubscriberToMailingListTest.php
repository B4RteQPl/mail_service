<?php

namespace Tests\Feature\Service\SubscriberService\Feature;

use App\Exceptions\Service\SubscriberService\MailingListWrongTypeException;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\SubscriberService;
use Tests\Feature\Service\SubscriberService\Traits\MailingListProviderTrait;
use Tests\TestCase;

class AddSubscriberToMailingListTest extends TestCase
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
    public function when_new_subscriber_is_added_to_group_then_subscriber_has_new_mailing_list($mailProvider)
    {
        // given
        $this->assertTrue($this->subscriber->hasNoMailingLists());
        $this->subscriberService = new SubscriberService($mailProvider());
        $mailingList = $this->getMailingList($mailProvider());

        // when
        $subscriber = $this->subscriberService->addSubscriberToMailingList($this->subscriber, $mailingList);

        // then
        $this->assertFalse($subscriber->hasNoMailingLists());
        $this->assertTrue($subscriber->hasMailingList($mailingList));
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_subscriber_has_mailing_list_then_adding_again_will_not_duplicate_list($mailProvider)
    {
        // given
        $this->subscriberService = new SubscriberService($mailProvider());
        $mailingList = $this->getMailingList($mailProvider());

        // when
        // fake subscriber is added to group
        $subscriber = $this->subscriberService->addSubscriberToMailingList($this->subscriber, $mailingList);
        $this->assertTrue($subscriber->hasMailingList($mailingList));
        $this->assertCount(1, $subscriber->getMailingLists());

        // add again subscriber to group
        $subscriber = $this->subscriberService->addSubscriberToMailingList($subscriber, $mailingList);

        // then
        $this->assertTrue($subscriber->hasMailingList($mailingList));
        $this->assertCount(1, $subscriber->getMailingLists());
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_subscriber_has_mailing_list_and_is_added_to_new_then_subscriber_has_exists_in_both_mailing_lists($mailProvider)
    {
        // given
        $this->subscriberService = new SubscriberService($mailProvider());
        $mailingList = $this->getMailingList($mailProvider());
        $nextMailingList = $this->getNextMailingList($mailProvider());

        // when
        // fake subscriber is added to group
        $subscriber = $this->subscriberService->addSubscriberToMailingList($this->subscriber, $mailingList);
        $this->assertTrue($subscriber->hasMailingList($mailingList));
        $this->assertCount(1, $subscriber->getMailingLists());

        // add again subscriber to group

        $subscriber = $this->subscriberService->addSubscriberToMailingList($subscriber, $nextMailingList);

        // then
        $this->assertTrue($subscriber->hasMailingList($mailingList));
        $this->assertTrue($subscriber->hasMailingList($nextMailingList));
        $this->assertCount(2, $subscriber->getMailingLists());
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_mailing_lists_has_different_type_than_mail_provider_type_then_throw_exception($mailProvider) {
        // given
        $this->subscriberService = new SubscriberService($mailProvider());

        $differentMailingList = new MailingList('id', 'name', 'invalid');

        // expect
        $this->expectException(MailingListWrongTypeException::class);
        $this->expectExceptionMessage('Cannot add subscriber to mailing list, because mailing list type is different than mail provider type');

        // when
        $this->subscriberService->addSubscriberToMailingList($this->subscriber, $differentMailingList);
    }

}
