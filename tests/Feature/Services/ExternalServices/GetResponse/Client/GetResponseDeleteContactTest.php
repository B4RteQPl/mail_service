<?php

namespace Tests\Feature\Services\ExternalServices\GetResponse\Client;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class GetResponseDeleteContactTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function delete_contact_should_return_true_and_prepare_user_for_next_test_session()
    {
        // this test can fail if it will be run too many times in short period of time, because of GetResponse API
        // asynchronous processing of requests

        $emails = [new Email('email-to-delete@email.com'), new Email('email-to-delete-2@email.com')];
        $campaignId = $this->getResponse()->client->getCampaignList()[0]['campaignId'];

        $isTested = false;
        foreach ($emails as $email) {
            // skip rest of the loop if already tested
            if ($isTested) {
                break;
            }

            $contact =  $this->getResponse()->client->getContactsFromCampaign($email, $campaignId);
            $contactId = $contact['contact']['contactId'] ?? null;
            if ($contactId) {
                $result = $this->getResponse()->client->deleteContact($contactId);
                if ($result === true) {
                    $isTested = true;
                }
            }
        }

        // create contacts for next test
        foreach ($emails as $email) {
            $contact = $this->getResponse()->client->getContactsFromCampaign($email, $campaignId);
            $contactId = $contact['contact']['contactId'] ?? null;
            if (!$contactId) {
                $this->getResponse()->client->createContact($email, $campaignId);
            }
        }

        $this->assertTrue($isTested);
    }
}
