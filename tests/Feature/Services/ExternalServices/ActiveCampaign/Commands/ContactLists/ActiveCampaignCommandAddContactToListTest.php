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
        $listId = $this->activeCampaign()->retrieveAllLists->execute()[0]['id'];

        $newUser = $this->getNewUser();
        $newUser['listId'] = $listId;

        $result = $this->activeCampaign()->addContactToList->execute($newUser);

        $this->assertArrayHasKey('contact', $result);
        $this->assertArrayHasKey('contactList', $result);
    }

    /**
     * @test
     */
    public function get_config()
    {
//        $config = $this->activeCampaign()->setup();
        $config = $this->activeCampaign()->addContactToList->getConfig();
        dump($config);
//        dump($config['parameters']['listId']['options']);

//        dump($config);
        $this->assertConfigRequiredFields($config);
        $this->assertConfigParams($config, ['email', 'firstName', 'lastName']);
    }
}
