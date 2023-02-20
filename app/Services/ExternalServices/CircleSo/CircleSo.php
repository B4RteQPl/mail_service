<?php

namespace App\Services\ExternalServices\CircleSo;

use App\Services\ExternalServices\AbstractCommandLoader;
use App\Services\ExternalServices\CircleSo\Client\CircleSoClient;
use App\Services\ExternalServices\CircleSo\Commands\Accounts\CircleSoCommandGetCommunityList;
use App\Services\ExternalServices\CircleSo\Commands\Members\CircleSoCommandInviteMember;
use App\Services\ExternalServices\CircleSo\Commands\Members\CircleSoCommandRemoveMemberFromCommunity;
use App\Services\ExternalServices\CircleSo\Commands\Members\CircleSoCommandSearchMember;
use App\Services\ExternalServices\CircleSo\Commands\SpaceGroups\CircleSoCommandAddMemberToSpaceGroup;
use App\Services\ExternalServices\CircleSo\Commands\SpaceGroups\CircleSoCommandGetMemberFromSpaceGroup;
use App\Services\ExternalServices\CircleSo\Commands\SpaceGroups\CircleSoCommandGetSpaceGroups;
use App\Services\ExternalServices\CircleSo\Commands\SpaceGroups\CircleSoCommandRemoveMemberFromSpaceGroups;
use App\Services\ExternalServices\CircleSo\Commands\Spaces\CircleSoCommandAddMemberToSpace;
use App\Services\ExternalServices\CircleSo\Commands\Spaces\CircleSoCommandGetMemberFromSpace;
use App\Services\ExternalServices\CircleSo\Commands\Spaces\CircleSoCommandGetSpaces;
use App\Services\ExternalServices\CircleSo\Commands\Spaces\CircleSoCommandRemoveMemberFromSpace;

class CircleSo extends AbstractCommandLoader
{
    protected ?CircleSoClient $client = null;

    const ACTIVE_COMMANDS = [
        CircleSoCommandGetCommunityList::class => 'getCommunityList',
        CircleSoCommandInviteMember::class => 'inviteMember',
        CircleSoCommandRemoveMemberFromCommunity::class => 'removeMemberFromCommunity',
        CircleSoCommandSearchMember::class => 'searchMember',
        CircleSoCommandAddMemberToSpaceGroup::class => 'addMemberToSpaceGroup',
        CircleSoCommandGetMemberFromSpaceGroup::class => 'getMemberFromSpaceGroup',
        CircleSoCommandGetSpaceGroups::class => 'getSpaceGroups',
        CircleSoCommandRemoveMemberFromSpaceGroups::class => 'removeMemberFromSpaceGroups',
        CircleSoCommandAddMemberToSpace::class => 'addMemberToSpace',
        CircleSoCommandGetMemberFromSpace::class => 'getMemberFromSpace',
        CircleSoCommandGetSpaces::class => 'getSpaces',
        CircleSoCommandRemoveMemberFromSpace::class => 'removeMemberFromSpace',
    ];

    public function __construct(?CircleSoClient $client = null)
    {
        if ($client) {
            $this->client = $client;
        }

        parent::__construct();
    }

    static public function setClient(string $apiKey): CircleSo
    {
        $client = new CircleSoClient($apiKey);

        return new CircleSo($client);
    }
}
