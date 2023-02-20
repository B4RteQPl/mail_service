<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Commands\Lists;

use Tests\CommandTestCase;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;

class ActiveCampaignCommandRetrieveAllListsTest extends CommandTestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function execute()
    {
        $allLists = $this->activeCampaign()->retrieveAllLists->execute();

        dump($allLists);

        $this->assertNotEmpty($allLists);
        $this->assertIsArray($allLists);
        $this->assertIsArray($allLists);
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
