<?php

namespace Tests\Feature\Services\ExternalServices\GetResponse\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class GetResponseGetCampaignListTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_tag_subscriber_called_for_new_contact_then_new_subscriber_with_tag_is_created()
    {
        $campaigns = $this->getResponse()->client->getCampaignList();

        $this->assertIsArray($campaigns);
        $this->assertNotEmpty($campaigns);
        $this->assertCount(2, $campaigns);

        $this->assertArrayHasKey('campaignId', $campaigns[0]);
        $this->assertArrayHasKey('name', $campaigns[0]);
        $this->assertArrayHasKey('description', $campaigns[0]);

        $this->assertArrayHasKey('campaignId', $campaigns[1]);
        $this->assertArrayHasKey('name', $campaigns[1]);
        $this->assertArrayHasKey('description', $campaigns[1]);
    }
}
