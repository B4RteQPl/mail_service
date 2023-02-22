<?php

namespace Tests\Feature\Services\ExternalServices\Mailchimp\Client;

use Tests\Feature\Services\ExternalServices\Mailchimp\MailchimpTestCase;

class MailchimpGetAllListsTest extends MailchimpTestCase
{

    /**
     * @test
     */
    public function when_get_all_lists_then_returns_array_of_all_lists()
    {
        $lists = $this->mailchimp()->client->getAllLists();

        $this->assertNotEmpty($lists);
        $this->assertCount(1, $lists);

        $this->assertArrayHasKey('id', $lists[0]);
        $this->assertArrayHasKey('name', $lists[0]);
    }
}
