<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\SpaceGroups;

use Tests\Feature\Services\ExternalServices\CircleSo\CircleSoTestCase;

class CircleSoRemoveMemberFromSpaceGroupTest extends CircleSoTestCase
{

    /**
     * @test
     */
    public function when_remove_member_from_space_group_then_return_true()
    {
        // given
        $user = $this->getCircleSoUser();
        $spaceGroupId = $this->circleSo()->client->getSpaceGroups($user['communityId'])[1]['id'];

        // when
        $result = $this->circleSo()->client->addMemberToSpaceGroup($user['communityId'], $user['email'], $spaceGroupId);

        // then
        $result = $this->circleSo()->client->removeMemberFromSpaceGroup($user['communityId'], $user['email'], $spaceGroupId);
        $this->assertTrue($result);

        // confirm deletion by search user
        $result = $this->circleSo()->client->getMemberFromSpaceGroup($user['communityId'], $user['email'], $spaceGroupId);
        $this->assertEmpty($result);
    }
}
