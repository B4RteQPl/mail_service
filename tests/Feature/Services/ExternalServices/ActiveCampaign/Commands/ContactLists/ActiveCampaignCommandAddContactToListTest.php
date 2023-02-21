<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Commands\ContactLists;

use Tests\CommandTestCase;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;

class ActiveCampaignCommandAddContactToListTest extends CommandTestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function execute()
    {
        $newUser = [
            'email' => $this->getUniqueEmail(),
            'firstName' => 'John',
            'lastName' => 'Snow',
            'listId' => $this->activeCampaign()->retrieveAllLists->execute()[0]['id'],
        ];

        $result = $this->activeCampaign()->addContactToList->execute($newUser);

        $this->assertArrayHasKey('contact', $result);
        $this->assertArrayHasKey('contactList', $result);
    }

    /**
     * @test
     */
    public function get_config()
    {
        $config = $this->activeCampaign()->addContactToList->getConfig();

        $this->assertConfigRequiredFields($config);
        $this->assertConfigFields($config, ['listId']);
    }
}
