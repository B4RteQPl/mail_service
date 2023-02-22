<?php

namespace App\Services\ExternalServices\CircleSo\Client;

use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

interface CircleSoClientInterface
{
    public function isConnectionOk(): ?bool;
    public function getCommunityList();
    public function searchMember(string $communityId, Email $email);
    public function getSpaceGroups(string $communityId);
    public function getMemberFromSpaceGroup(string $communityId, Email $email, string $spaceGroupId);
    public function inviteMember(string $communityId, Email $email, FirstName $firstName, LastName $lastName, array $spaceIds, array $spaceGroupIds);
    public function removeMemberFromCommunity(string $communityId, Email $email);
    public function getMemberFromSpace(string $communityId, Email $email, string $spaceId);
    public function addMemberToSpace(string $communityId, Email $email, string $spaceId);
    public function getSpaces(string $communityId);
    public function removeMemberFromSpace(string $communityId, Email $email, string $spaceId);
    public function addMemberToSpaceGroup(string $communityId, Email $email, string $spaceGroupId);
    public function removeMemberFromSpaceGroup(string $communityId, Email $email, string $spaceGroupId);
}
