<?php

namespace Tests\Feature\Service\MailService;

use App\Exceptions\Service\MailService\CannotDeleteSubscriberFromGroupException;
use App\Service\MailService\MailService;
use Tests\Feature\Service\MailService\Traits\MailProvidersTrait;
use Tests\TestCase;

class DeleteSubscriberFromGroupTest extends TestCase
{

    use MailProvidersTrait;

    protected string $email;
    protected string $name;
    protected string $groupId;
    protected string $anotherGroupId;

    public function setUp(): void {
        parent::setUp();
        $uniqueEmail = uniqid('test-', true) . '@fakedomain.com';

        $this->email = $uniqueEmail;
        $this->name = 'Fake name';
        $this->groupId = '78555075519186749';
        $this->anotherGroupId = '78557642515023076';
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_subscriber_has_no_groups_then_deleteSubscriberFromGroup_return_true($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());

        // when
        $isDeleted = $mailService->deleteSubscriberFromGroup($this->email, $this->groupId);

        // then
        $this->assertTrue($isDeleted);
    }

    /**
     * @test
     * @dataProvider mailProviders
     * @throws CannotDeleteSubscriberFromGroupException
     */
    public function when_subscriber_is_successfully_deleted_then_deleteSubscriberFromGroup_should_return_true($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());
        $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);

        // when
        $isDeleted = $mailService->deleteSubscriberFromGroup($this->email, $this->groupId);

        // then
        $this->assertTrue($isDeleted);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_subscriber_has_multiple_groups_then_deleteSubscriberFromGroup_deletes_only_group_to_delete($mailProvider)
    {
        // given
        $groupIdToDelete = $this->anotherGroupId;
        $mailService = new MailService($mailProvider());

        $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);
        $mailService->addSubscriberToGroup($this->email, $this->name, $groupIdToDelete);

        // when
        $isDeletedFromGroup = $mailService->deleteSubscriberFromGroup($this->email, $groupIdToDelete);

        // then
        $this->assertTrue($isDeletedFromGroup);

        // extra check
        $subscriber = $mailService->getSubscriber($this->email);
        $this->assertArrayHasKey('groups', $subscriber);
        $this->assertContains($this->groupId, $subscriber['groups']);
        $this->assertNotContains($groupIdToDelete, $subscriber['groups']);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_email_is_empty_then_deleteSubscriberFromGroup_throws_exception($mailProvider)
    {
        // given
        $this->email = '';
        $mailService = new MailService($mailProvider());

        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        $mailService->deleteSubscriberFromGroup($this->email, $this->groupId);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_groupId_is_empty_then_deleteSubscriberFromGroup_throws_exception($mailProvider)
    {
        // given
        $this->groupId = '';
        $mailService = new MailService($mailProvider());

        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        $mailService->deleteSubscriberFromGroup($this->email, $this->groupId);
    }

}
