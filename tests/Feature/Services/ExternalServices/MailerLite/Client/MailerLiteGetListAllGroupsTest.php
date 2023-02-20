<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteGetListAllGroupsTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_get_list_all_groups_then_returns_list_of_groups()
    {
        $groups = $this->mailerLite()->client->getListAllGroups();

        $this->assertIsArray($groups);
        $this->assertNotEmpty($groups);

        $this->assertArrayHasKey('id', $groups[0]);
        $this->assertArrayHasKey('name', $groups[0]);

        $this->assertArrayHasKey('id', $groups[1]);
        $this->assertArrayHasKey('name', $groups[1]);
    }
}
