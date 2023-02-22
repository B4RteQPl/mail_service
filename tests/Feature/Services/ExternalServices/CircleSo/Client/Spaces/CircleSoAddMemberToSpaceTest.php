<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\Spaces;

use Tests\Feature\Services\ExternalServices\CircleSo\CircleSoTestCase;

class CircleSoAddMemberToSpaceTest extends CircleSoTestCase
{

    /**
     * @test
     */
    public function when_add_member_to_space_group_then_return_true()
    {
        $user = $this->getCircleSoUser();
        $spaces = $this->circleSo()->client->getSpaces($user['communityId']);
        $firstSpace = $spaces[2];
        $spaceId = $firstSpace['id'];

        $result = $this->circleSo()->client->addMemberToSpace($user['communityId'], $user['email'], $spaceId);
        $this->assertTrue($result);

        // clean after tests
        $this->circleSo()->client->removeMemberFromSpace($user['communityId'], $user['email'], $spaceId);
    }
}
