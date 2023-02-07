<?php

namespace Tests\Feature\Service\SubscriberService\Feature;

// todo exception is not tested yet
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\MailingListWrongTypeException;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\MailProviders\ConvertKit\ConvertKitMailProvider;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\SubscriberService;
use Tests\Feature\Service\SubscriberService\Traits\MailingListProviderTrait;
use Tests\TestCase;

class DeleteSubscriberFromMailingListTest extends TestCase
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
    public function when_subscriber_has_no_mailing_list_then_after_delete_subscriber_from_mailing_list_subscriber_is_not_changed_empty($mailProvider)
    {
        // given
        $this->assertTrue($this->subscriber->hasNoMailingLists());

        $this->subscriberService = new SubscriberService($mailProvider());
        $mailingList = $this->getMailingList($mailProvider());

        if ($mailProvider() instanceof ConvertKitMailProvider) {
            $mailingList = $mailingList ?? $this->getMailingList($mailProvider());

            $subscriberVerified = $this->subscriberService->addSubscriberToMailingList($this->subscriber, $mailingList);
            $subscriberVerified = $this->subscriberService->deleteSubscriberFromMailingList($subscriberVerified, $mailingList);
        } else {
            $subscriberVerified = $this->subscriberService->addSubscriber($this->subscriber);
        }

        $this->expectException(CannotDeleteSubscriberFromMailingListException::class);
        $this->expectExceptionMessage('Cannot delete subscriber, because is not assigned to mailing list');
//
        $this->subscriberService->deleteSubscriberFromMailingList($subscriberVerified, $mailingList);
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_subscriber_is_assigned_to_many_mailing_lists_then_delete_removes_only_deleted_list($mailProvider)
    {
        // given
        $this->assertTrue($this->subscriber->hasNoMailingLists());

        $this->subscriberService = new SubscriberService($mailProvider());
        $mailingList = $this->getMailingList($mailProvider());
        $nextMailingList = $this->getNextMailingList($mailProvider());

        $subscriber = $this->subscriberService->addSubscriberToMailingList($this->subscriber, $mailingList);
        $subscriber = $this->subscriberService->addSubscriberToMailingList($subscriber, $nextMailingList);

        // when
        $this->assertTrue($subscriber->hasMailingList($mailingList));
        $this->assertTrue($subscriber->hasMailingList($nextMailingList));
        $this->assertCount(2, $subscriber->getMailingLists());

        sleep(0.2);
        $subscriber = $this->subscriberService->deleteSubscriberFromMailingList($subscriber, $mailingList);
        $this->assertFalse($subscriber->hasMailingList($mailingList));
        $this->assertTrue($subscriber->hasMailingList($nextMailingList));
        $this->assertCount(1, $subscriber->getMailingLists());

        sleep(0.2);
        $subscriber = $this->subscriberService->deleteSubscriberFromMailingList($subscriber, $nextMailingList);
        $this->assertFalse($subscriber->hasMailingList($mailingList));
        $this->assertFalse($subscriber->hasMailingList($nextMailingList));
        $this->assertCount(0, $subscriber->getMailingLists());
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_mailing_lists_has_different_type_than_provider_type_then_throw_exception($mailProvider) {
        // given
        $this->subscriberService = new SubscriberService($mailProvider());
        $differentMailingList = new MailingList('id', 'name', 'invalid');

        // expect
        $this->expectException(MailingListWrongTypeException::class);
        $this->expectExceptionMessage('Cannot delete subscriber from mailing list, because mailing list type is different than mail provider type');

        // when
        $this->subscriberService->deleteSubscriberFromMailingList($this->subscriber, $differentMailingList);
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_subscriber_is_deleted_manually_from_mailing_list_then_throw_exception ($mailProvider)
    {
        $this->markTestSkipped('to clarify');
    }

    /**
     * @test
     * @dataProvider validMailProviders
     */
    public function when_mailing_list_is_deleted_from_account_manually_then_throw_exception ($mailProvider)
    {
        $this->markTestSkipped('to clarify');
    }

}
