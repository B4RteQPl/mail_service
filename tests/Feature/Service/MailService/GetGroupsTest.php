<?php

namespace Tests\Feature\Service\MailService;

use App\Service\MailService\MailService;
use Tests\Feature\Service\MailService\Traits\MailProvidersTrait;
use Tests\TestCase;

class GetGroupsTest extends TestCase
{

    use MailProvidersTrait;

    /**
     * FOR MANUAL PURPOSES ONLY
     *
     * @test
     * @dataProvider mailProviders
     */
    public function when_account_has_no_groups_then_getGroups_returns_empty_array($mailProvider)
    {
        // skip this test because MailService not provides possibility to create groups, so flow is not testable
        $this->markTestSkipped();

        // given
        $mailService = new MailService($mailProvider());

        // when
        $allGroups = $mailService->getGroups();

        // then
        $this->assertIsArray($allGroups);
        $this->assertEmpty($allGroups);
    }

    /**
     * FOR MANUAL PURPOSES ONLY
     *
     * @test
     * @dataProvider mailProviders
     */
    public function when_account_has_groups_then_getGroups_returns_array_of_groupsDTO($mailProvider)
    {
        // skip this test because MailService not provides possibility to create groups, so flow is not testable
        $this->markTestSkipped();

        // given
        $mailService = new MailService($mailProvider());

        // when
        $allGroups = $mailService->getGroups();

        // then
        $this->validateGroupsDTO($allGroups);
    }

    private function validateGroupsDTO($groupsDTO): void
    {
        $this->assertIsArray($groupsDTO);

        $this->assertArrayHasKey('id', $groupsDTO);
        $this->assertArrayHasKey('name', $groupsDTO);
    }
}
