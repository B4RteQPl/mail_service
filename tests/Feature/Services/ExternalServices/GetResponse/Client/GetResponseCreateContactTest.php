<?php

namespace Tests\Feature\Services\ExternalServices\GetResponse\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class GetResponseCreateContactTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function should_create_new_contact_for_given_campaign_id()
    {
        $contact = $this->getNewUser();

        $campaignId = $this->getResponse()->client->getCampaignList()[0]['campaignId'];
        $result = $this->getResponse()->client->createContact($contact['email'], $campaignId);

        // Asynchronous call, so we can't check the result
        $this->assertTrue($result);
    }
}
