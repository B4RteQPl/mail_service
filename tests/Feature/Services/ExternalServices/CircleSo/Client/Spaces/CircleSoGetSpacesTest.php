<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\Spaces;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSoGetSpacesTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_get_space_groups_should_return_array_of_space_groups()
    {
        // given
        $user = $this->getCircleSoUser();
        $communityId = $user['communityId'];

        // when
        $result = $this->circleSo()->client->getSpaces($communityId);

        // then
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('slug', $result[0]);
        $this->assertArrayHasKey('space_group_id', $result[0]);
        $this->assertArrayHasKey('space_group_name', $result[0]);
        $this->assertArrayHasKey('url', $result[0]);
        $this->assertArrayHasKey('community_id', $result[0]);
        $this->assertArrayHasKey('is_private', $result[0]);
    }
}
