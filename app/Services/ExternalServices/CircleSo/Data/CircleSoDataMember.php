<?php

namespace App\Services\ExternalServices\CircleSo\Data;

use App\Services\ExternalServices\BaseEntity;

class CircleSoDataMember extends BaseEntity
{
    protected array $config = array('id', 'email', 'first_name', 'last_name', 'community_member_id');
}

