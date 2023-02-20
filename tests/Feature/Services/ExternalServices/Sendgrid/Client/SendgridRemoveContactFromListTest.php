<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid\Client;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class SendgridRemoveContactFromListTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_remove_group_from_subscriber_then_subscriber_is_removed_from_group()
    {
        $emails = [new Email('email-to-delete@email.com'), new Email('email-to-delete-2@email.com')];
        $listId = $this->sendgrid()->client->getAllLists()[0]['id'];

        $isTested = false;
        foreach ($emails as $email) {
            $contact = $this->sendgrid()->client->getContactsByEmail($email);
            if($contact) {
                $isTested = true;
                $result = $this->sendgrid()->client->removeContactFromList($contact['id'], $listId);

                $this->assertArrayHasKey('job_id', $result);
            } else {
                $this->sendgrid()->client->addContactToList($email, $listId);
            }
        }

        if ($isTested) {
            $this->assertTrue(true);
            return;
        } else {
            $this->markTestSkipped('Creating contact in progress, try again later');
        }

//        $contact = $this->getNewUser();
//
//        foreach ($emails as $email) {
//            if ($isTested) {
//                break;
//            }
//
//            $result = $this->sendgrid()->client->removeContactFromList($email, $listId);
//
//            dump($result);
//        }

//        $this->assertTrue($isTested);
    }
}
