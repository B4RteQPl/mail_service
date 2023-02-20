<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\Members;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSearchMemberTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function should_search_member_in_community_using_email()
    {
        $communityId = $this->circleSo()->client->getCommunityList()[0]['id'];
        $user = $this->getCircleSoUser();

        $result = $this->circleSo()->client->searchMember($communityId, $user['email']);

        $this->assertNotEmpty($result);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
        $this->assertArrayHasKey('community_id', $result);
        $this->assertArrayHasKey('member_tags', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('email', $result);
    }
}
