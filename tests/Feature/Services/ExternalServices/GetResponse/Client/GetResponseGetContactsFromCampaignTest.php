<?php

namespace Tests\Feature\Services\ExternalServices\GetResponse\Client;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class GetResponseGetContactsFromCampaignTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_user_is_just_created_then_gets_method_returns_empty_array()
    {
        $contact = $this->getNewUser();

        $campaignId = $this->getResponse()->client->getCampaignList()[0]['campaignId'];
        $this->getResponse()->client->createContact($contact['email'], $campaignId);

        $result =  $this->getResponse()->client->getContactsFromCampaign($contact['email'], $campaignId);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function when_user_exists_then_gets_contact_from_campaign_then_return_contact_and_campaign_data()
    {
        $campaignId = $this->getResponse()->client->getCampaignList()[0]['campaignId'];

        $result =  $this->getResponse()->client->getContactsFromCampaign(new Email('existing@email.com'), $campaignId);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('contact', $result);
        $this->assertArrayHasKey('campaign', $result);
        $this->assertArrayHasKey('contactId', $result['contact']);
        $this->assertArrayHasKey('name', $result['contact']);
        $this->assertArrayHasKey('email', $result['contact']);

        $this->assertArrayHasKey('campaignId', $result['campaign']);
        $this->assertArrayHasKey('name', $result['campaign']);
    }
}
