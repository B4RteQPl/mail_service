<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Client;

use Tests\Feature\Services\ExternalServices\ActiveCampaign\ActiveCampaignTestCase;

class ActiveCampaignRetrieveAllListsTest extends ActiveCampaignTestCase
{

    /**
     * @test
     */
    public function retrieve_all_lists_should_return_array_of_lists()
    {
        $arrayOfLists = $this->activeCampaign()->client->retrieveAllLists();

        $this->assertIsArray($arrayOfLists);
        $this->assertNotEmpty($arrayOfLists);
        $this->assertCount(2, $arrayOfLists);

        $this->assertArrayHasKey('id', $arrayOfLists[0]);
        $this->assertArrayHasKey('name', $arrayOfLists[0]);

        $this->assertArrayHasKey('id', $arrayOfLists[1]);
        $this->assertArrayHasKey('name', $arrayOfLists[1]);
    }
}
