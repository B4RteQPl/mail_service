<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\SpaceGroups;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSoGetSpaceGroupsTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_get_space_groups_should_return_array_of_space_groups()
    {
        $communityId = $this->circleSo()->client->getCommunityList()[0]['id'];
        $result = $this->circleSo()->client->getSpaceGroups($communityId);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('community_id', $result[0]);
        $this->assertArrayHasKey('automatically_add_members_to_new_spaces', $result[0]);
        $this->assertArrayHasKey('add_members_to_space_group_on_space_join', $result[0]);
        $this->assertArrayHasKey('slug', $result[0]);
    }
}
