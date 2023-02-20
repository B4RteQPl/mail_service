<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Commands\ContactLists;

use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClient;
use Tests\CommandTestCase;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;

class ActiveCampaignCommandRemoveContactFromListTest extends CommandTestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function execute()
    {
        $listId = $this->activeCampaign()->retrieveAllLists->execute()[0]['id'];

        $userToDelete = $this->getNewUser();
        $userToDelete['listId'] = $listId;

        $this->activeCampaign()->addContactToList->execute($userToDelete);

        $result = $this->activeCampaign()->removeContactFromList->execute($userToDelete);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('contact', $result);
        $this->assertArrayHasKey('email', $result['contact']);
        $this->assertArrayHasKey('firstName', $result['contact']);
        $this->assertArrayHasKey('lastName', $result['contact']);
        $this->assertArrayHasKey('id', $result['contact']);

        $this->assertArrayHasKey('contactList', $result);
        $this->assertArrayHasKey('contact', $result['contactList']);
        $this->assertArrayHasKey('list', $result['contactList']);
        $this->assertArrayHasKey('status', $result['contactList']);
        $this->assertArrayHasKey('id', $result['contactList']);

        $this->assertEquals(ActiveCampaignClient::LIST_STATUS_UNSUBSCRIBED, $result['contactList']['status']);
    }

    /**
     * @test
     */
    public function get_config()
    {
        $config = $this->activeCampaign()->addContactToList->getConfig();

        $this->assertConfigRequiredFields($config);
        $this->assertConfigParams($config, ['email']);
    }
}
