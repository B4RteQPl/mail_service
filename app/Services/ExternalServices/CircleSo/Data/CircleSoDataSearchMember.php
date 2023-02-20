<?php

namespace App\Services\ExternalServices\CircleSo\Data;

use App\Clients\BaseEntity;

class CircleSoDataSearchMember extends BaseEntity
{
    protected array $config = array('id', 'user_id', 'name', 'email', 'first_name', 'last_name', 'community_id', 'member_tags');
}

