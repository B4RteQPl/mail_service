<?php

namespace Tests\Feature\Service\MailService;

use App\Exceptions\Service\MailService\CannotAddSubscriberToGroupException;
use App\Service\MailService\MailService;
use Tests\Feature\Service\MailService\Traits\MailProvidersTrait;
use Tests\TestCase;

class AddSubscriberToGroupTest extends TestCase
{

    use MailProvidersTrait;

    protected string $email;
    protected string $name;
    protected string $groupId;
    protected string $anotherGroupId;

    public function setUp(): void {
        parent::setUp();
        $uniqueEmail = uniqid('test-') . '@fakedomain.com';

        $this->email = $uniqueEmail;
        $this->name = 'Fake name';
        $this->groupId = '78555075519186749';
        $this->anotherGroupId = '78557642515023076';
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_subscriber_already_exists_in_group_then_addSubscriberToGroup_returns_subscriberDTO($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());

        // when
        // fake subscriber is added to group
        $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);

        // add subscriber to group again
        $response = $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);

        // then
        $this->validateSubscriberDTO($response);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_new_subscriber_is_added_to_group_then_method_addSubscriberToGroup_returns_subscriberDTO($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());

        // when
        $response = $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);

        // then
        $this->validateSubscriberDTO($response);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_new_subscriber_has_other_group_then_addSubscriberToGroup_assigns_subscriber_to_new_group_without_changing_existing($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());
        $mailService->addSubscriberToGroup($this->email, $this->name, $this->anotherGroupId);

        // when
        $response = $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);

        // then
        $this->assertCount(2, $response['groups']);
        $this->assertContains($this->groupId, $response['groups']);
        $this->assertContains($this->anotherGroupId, $response['groups']);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_subscriber_name_is_empty_then_addSubscriberToGroup_returns_subscriberDTO_with_empty_name($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());
        $this->name = '';

        // when
        $response = $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);

        // then
        $this->validateSubscriberDTO($response);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_group_id_not_exists_then_addSubscriberToGroup_throws_exception($mailProvider)
    {
        // given
        $this->groupId = '';
        $mailService = new MailService($mailProvider());

        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_group_id_is_empty_then_addSubscriberToGroup_throws_exception($mailProvider)
    {
        // given
        $this->groupId = '11111111111111111';
        $mailService = new MailService($mailProvider());

        // expect
        $this->expectException(CannotAddSubscriberToGroupException::class);

        // when
        $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);
    }

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_email_is_not_valid_then_addSubscriberToGroup_throws_exception($mailProvider)
    {
        // given
        $this->email = '';
        $mailService = new MailService($mailProvider());

        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        $mailService->addSubscriberToGroup($this->email, $this->name, $this->groupId);
    }

    private function validateSubscriberDTO($subscriberDTO): void
    {
        $this->assertIsArray($subscriberDTO);

        $this->assertArrayHasKey('id', $subscriberDTO);
        $this->assertArrayHasKey('email', $subscriberDTO);
        $this->assertArrayHasKey('groups', $subscriberDTO);

        $this->assertEquals($subscriberDTO['email'], $this->email);
        $this->assertIsArray($subscriberDTO['groups']);
    }

}
