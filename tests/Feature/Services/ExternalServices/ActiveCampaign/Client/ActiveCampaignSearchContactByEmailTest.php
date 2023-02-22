<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Client;

use Tests\Feature\Services\ExternalServices\ActiveCampaign\ActiveCampaignTestCase;

class ActiveCampaignSearchContactByEmailTest extends ActiveCampaignTestCase
{

    /**
     * @test
     */
    public function search_contact_by_email_should_return_contact_when_contact_exists()
    {
        $contact = $this->getNewUser();

        $this->activeCampaign()->client->createNewContact($contact['email'], $contact['firstName'], $contact['lastName']);

        $result = $this->activeCampaign()->client->searchContactByEmail($contact['email']);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('firstName', $result);
        $this->assertArrayHasKey('lastName', $result);

        $this->deleteContact($result['id']);
    }

    /**
     * @test
     */
    public function search_contact_by_email_should_return_empty_array_when_contact_not_exists()
    {
        $contact = $this->getNewUser();

        $result = $this->activeCampaign()->client->searchContactByEmail($contact['email']);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
