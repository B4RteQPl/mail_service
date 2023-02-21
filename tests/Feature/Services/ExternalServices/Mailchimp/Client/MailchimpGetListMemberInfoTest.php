<?php

namespace Tests\Feature\Services\ExternalServices\Mailchimp\Client;

use Tests\Feature\Services\ExternalServices\Mailchimp\MailchimpTestCase;

class MailchimpGetListMemberInfoTest extends MailchimpTestCase
{

    /**
     * @test
     */
    public function when_get_is_member_info_then_returns_member_data()
    {
        $contact = $this->getNewUser();
        $listId = $this->mailchimp()->client->getAllLists()[0]['id'];
        $this->mailchimp()->client->addListMember($contact['email'], $listId);


        $result = $this->mailchimp()->client->getListMemberInfo($contact['email'], $listId);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email_address', $result);
        $this->assertArrayHasKey('contact_id', $result);
        $this->assertArrayHasKey('full_name', $result);
        $this->assertArrayHasKey('status', $result);

        $this->deleteListMemberPermanent($contact['email'], $listId);
    }
}
