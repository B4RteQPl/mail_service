<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo\Client\SpaceGroups;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class CircleSoGetSpaceGroupMembersTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_empty_space_group_then_should_return_empty_array()
    {
        $communityId = $this->circleSo()->client->getCommunityList()[0]['id'];
        $spaceGroupId = $this->circleSo()->client->getSpaceGroups($communityId)[0]['id'];
        $notExistingEmail = new Email('fakeunique@email.com');

        $result = $this->circleSo()->client->getMemberFromSpaceGroup($communityId, $notExistingEmail, $spaceGroupId);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }


    /**
     * @test
     */
    public function when_not_empty_space_group_then_should_return_array_of_members()
    {
        $user = $this->getCircleSoUser();
        $spaceGroupId = $this->circleSo()->client->getSpaceGroups($user['communityId'])[1]['id'];
        $this->circleSo()->client->addMemberToSpaceGroup($user['communityId'], $user['email'], $spaceGroupId);

        $result = $this->circleSo()->client->getMemberFromSpaceGroup($user['communityId'], $user['email'], $spaceGroupId);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('space_group_id', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('community_member_id', $result);

        // clean after test
        $this->circleSo()->client->removeMemberFromSpaceGroup($user['communityId'], $user['email'], $spaceGroupId);
    }
}
