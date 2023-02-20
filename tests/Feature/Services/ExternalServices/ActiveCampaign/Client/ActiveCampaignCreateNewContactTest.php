<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class ActiveCampaignCreateNewContactTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function create_new_contact_should_return_contact()
    {
        $contact = $this->getNewUser();

        $result = $this->activeCampaign()->client->createNewContact($contact['email'], $contact['firstName'], $contact['lastName']);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(4, $result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('firstName', $result);
        $this->assertArrayHasKey('lastName', $result);
    }
}
