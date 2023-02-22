<?php

namespace App\Services\ExternalServices\CircleSo\Data;

use App\Services\ExternalServices\BaseEntity;

class CircleSoDataSpace extends BaseEntity
{
    protected array $config = array('id', 'name', 'slug', 'space_group_id', 'space_group_name', 'url', 'community_id', 'is_private');
}

