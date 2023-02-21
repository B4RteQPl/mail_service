<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\SpaceGroups;

use Tests\Feature\Services\ExternalServices\CircleSo\CircleSoTestCase;

class CircleSoAddMemberToSpaceGroupTest extends CircleSoTestCase
{

    /**
     * @test
     */
    public function when_add_member_to_space_group_then_return_true()
    {
        // given
        $user = $this->getCircleSoUser();
        $communityId = $user['communityId'];
        $spaceGroupId = $this->circleSo()->client->getSpaceGroups($communityId)[1]['id'];

        // when
        $result = $this->circleSo()->client->addMemberToSpaceGroup($communityId, $user['email'], $spaceGroupId);

        // then
        $this->assertTrue($result);

        // clean after tests
        $this->circleSo()->client->removeMemberFromSpaceGroup($communityId, $user['email'], $spaceGroupId);
    }
}
