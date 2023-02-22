<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid\Client;

use Tests\Feature\Services\ExternalServices\Sendgrid\SendgridTestCase;

class SendgridGetAllListsTest extends SendgridTestCase
{

    /**
     * @test
     */
    public function when_get_list_all_groups_then_returns_list_of_groups()
    {
        $lists = $this->sendgrid()->client->getAllLists();

        $this->assertIsArray($lists);
        $this->assertNotEmpty($lists);
        $this->assertCount(2, $lists);

        $this->assertArrayHasKey('id', $lists[0]);
        $this->assertArrayHasKey('name', $lists[0]);

        $this->assertArrayHasKey('id', $lists[1]);
        $this->assertArrayHasKey('name', $lists[1]);
    }
}
