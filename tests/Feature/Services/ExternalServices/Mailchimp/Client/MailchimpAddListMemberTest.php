<?php

namespace Tests\Feature\Services\ExternalServices\Mailchimp\Client;

use Tests\Feature\Services\ExternalServices\Mailchimp\MailchimpTestCase;

class MailchimpAddListMemberTest extends MailchimpTestCase
{

    /**
     * @test
     */
    public function when_success_then_returns_new_member()
    {
        $contact = $this->getNewUser();
        $listId = $this->mailchimp()->client->getAllLists()[0]['id'];

        $result = $this->mailchimp()->client->addListMember($contact['email'], $listId);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email_address', $result);
        $this->assertArrayHasKey('contact_id', $result);
        $this->assertArrayHasKey('full_name', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('list_id', $result);

        $this->assertMemberHasList($contact['email'], $listId);
        $this->deleteListMemberPermanent($contact['email'], $listId);
    }
}
