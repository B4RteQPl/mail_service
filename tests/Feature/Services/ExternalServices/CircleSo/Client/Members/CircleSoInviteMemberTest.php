<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\Members;

use Tests\Feature\Services\ExternalServices\CircleSo\CircleSoTestCase;

class CircleSoInviteMemberTest extends CircleSoTestCase
{

    /**
     * @test
     */
    public function should_invite_new_member_to_community()
    {
        $communityId = $this->circleSo()->client->getCommunityList()[0]['id'];
        $spaceGroupIds = [$this->circleSo()->client->getSpaceGroups($communityId)[0]['id']];
        $user = $this->getNewUser();

        $result = $this->circleSo()->client->inviteMember($communityId, $user['email'], $user['firstName'], $user['lastName'], [], $spaceGroupIds);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
        $this->assertArrayHasKey('community_member_id', $result);

        // clean after tests
        $this->assertTrue($this->circleSo()->client->removeMemberFromCommunity($communityId, $user['email']));
    }
}
