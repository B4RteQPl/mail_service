<?php

namespace Tests\Feature\Services\ExternalServices\Mailchimp\Client;

use Tests\Feature\Services\ExternalServices\Mailchimp\MailchimpTestCase;

class MailchimpDeleteListMemberTest extends MailchimpTestCase
{

    /**
     * @test
     */
    public function when_delete_list_member_then_returns_empty_response()
    {
        $contact = $this->getNewUser();
        $listId = $this->mailchimp()->client->getAllLists()[0]['id'];
        $this->mailchimp()->client->addListMember($contact['email'], $listId);

        $isDeleted = $this->mailchimp()->client->deleteListMember($contact['email'], $listId);

        $this->assertTrue($isDeleted);

        $this->deleteListMemberPermanent($contact['email'], $listId);
    }
}
