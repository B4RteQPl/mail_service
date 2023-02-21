<?php

namespace Tests\Feature\Services\ExternalServices\Mailchimp;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class MailchimpTestCase extends ExternalServicesTestCase
{
    public function mailchimp()
    {
        return $this->externalServices()->mailchimp->setClient(env('TEST_MAILCHIMP_API_KEY'));
    }

    protected function assertMemberHasList(Email $email, string $listId)
    {
        $member = $this->mailchimp()->client->getListMemberInfo($email, $listId);

        $this->assertArrayHasKey('list_id', $member);
        $this->assertEquals($listId, $member['list_id']);
    }

    protected function deleteListMemberPermanent(Email $email, string $listId)
    {
        $isDeleted = $this->mailchimp()->client->deleteListMemberPermanent($email, $listId);
        $this->assertTrue($isDeleted);
    }

}
