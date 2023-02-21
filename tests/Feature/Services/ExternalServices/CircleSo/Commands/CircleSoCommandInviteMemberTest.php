<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSoGetCommunityListsTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_get_community_lists_should_return_array_of_comminities()
    {
        $result = $this->circleSo()->inviteMember->getConfig();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('slug', $result[0]);
        $this->assertArrayHasKey('owner_id', $result[0]);
        $this->assertArrayHasKey('is_private', $result[0]);
        $this->assertArrayHasKey('space_ids', $result[0]);
    }
}
