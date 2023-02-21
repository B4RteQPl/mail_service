<?php

namespace App\Services\ExternalServices\CircleSo\Data;

use App\Services\ExternalServices\BaseEntity;

class CircleSoDataSpaceGroupMember extends BaseEntity
{
    protected array $config = array('id', 'user_id', 'space_group_id', 'status', 'community_member_id');
}

