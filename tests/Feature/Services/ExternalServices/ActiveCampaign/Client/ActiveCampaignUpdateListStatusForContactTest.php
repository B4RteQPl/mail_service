<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Client;

use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use Tests\Feature\Services\ExternalServices\ActiveCampaign\ActiveCampaignTestCase;

class ActiveCampaignUpdateListStatusForContactTest extends ActiveCampaignTestCase
{

    /**
     * @test
     */
    public function should_update_list_status_for_contact_to_subscribed()
    {
        $contact = $this->getNewUser();

        $contactId = $this->activeCampaign()->client->createNewContact($contact['email'], $contact['firstName'], $contact['lastName'])['id'];
        $listId = $this->activeCampaign()->client->retrieveAllLists()[0]['id'];

        $result = $this->activeCampaign()->client->updateListStatusForContact($listId, $contactId, ActiveCampaignClient::LIST_STATUS_SUBSCRIBED);
        dump($result);
        $this->assertArrayHasKey('contact', $result);
        $this->assertArrayHasKey('id', $result['contact']);
        $this->assertArrayHasKey('email', $result['contact']);
        $this->assertArrayHasKey('firstName', $result['contact']);
        $this->assertArrayHasKey('lastName', $result['contact']);

        $this->assertArrayHasKey('contactList', $result);
        $this->assertArrayHasKey('id', $result['contactList']);
        $this->assertArrayHasKey('status', $result['contactList']);
        $this->assertArrayHasKey('list', $result['contactList']);
        $this->assertArrayHasKey('contact', $result['contactList']);

        $this->assertEquals(ActiveCampaignClient::LIST_STATUS_SUBSCRIBED, $result['contactList']['status']);

        $this->deleteContact($result['contact']['id']);
    }

    /**
     * @test
     */
    public function should_update_list_status_for_contact_to_unsubscribed()
    {
        $contact = $this->getNewUser();

        $contactId = $this->activeCampaign()->client->createNewContact($contact['email'], $contact['firstName'], $contact['lastName'])['id'];
        $listId = $this->activeCampaign()->client->retrieveAllLists()[0]['id'];

        $result = $this->activeCampaign()->client->updateListStatusForContact($listId, $contactId, ActiveCampaignClient::LIST_STATUS_UNSUBSCRIBED);

        $this->assertArrayHasKey('contact', $result);
        $this->assertArrayHasKey('id', $result['contact']);
        $this->assertArrayHasKey('email', $result['contact']);
        $this->assertArrayHasKey('firstName', $result['contact']);
        $this->assertArrayHasKey('lastName', $result['contact']);

        $this->assertArrayHasKey('contactList', $result);
        $this->assertArrayHasKey('id', $result['contactList']);
        $this->assertArrayHasKey('status', $result['contactList']);
        $this->assertArrayHasKey('list', $result['contactList']);
        $this->assertArrayHasKey('contact', $result['contactList']);

        $this->assertEquals(ActiveCampaignClient::LIST_STATUS_UNSUBSCRIBED, $result['contactList']['status']);

        $this->deleteContact($result['contact']['id']);
    }
}
