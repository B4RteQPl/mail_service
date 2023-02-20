<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\Members;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSoRemoveMemberFromCommunityTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function should_remove_member_from_community()
    {
        $communityId = $this->circleSo()->client->getCommunityList()[0]['id'];
        $spaceGroupIds = [$this->circleSo()->client->getSpaceGroups($communityId)[0]['id']];
        $user = $this->getNewUser();

        $this->circleSo()->client->inviteMember($communityId, $user['email'], $user['firstName'], $user['lastName'], [], $spaceGroupIds);

        $result = $this->circleSo()->client->removeMemberFromCommunity($communityId, $user['email']);

        $this->assertTrue($result);
    }
}
