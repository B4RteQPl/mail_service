<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\Spaces;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSoRemoveMemberFromSpaceTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_remove_member_from_space_group_then_return_true()
    {
        // given
        $user = $this->getCircleSoUser();
        $spaces = $this->circleSo()->client->getSpaces($user['communityId']);
        $firstSpace = $spaces[1];
        $spaceId = $firstSpace['id'];

        // when
        $this->circleSo()->client->addMemberToSpace($user['communityId'], $user['email'], $spaceId);

        // then
        $result = $this->circleSo()->client->removeMemberFromSpace($user['communityId'], $user['email'], $spaceId);
        $this->assertTrue($result);

        // confirm deletion by search user
        $result = $this->circleSo()->client->getMemberFromSpace($user['communityId'], $user['email'], $spaceId);
        $this->assertEmpty($result);
    }
}
