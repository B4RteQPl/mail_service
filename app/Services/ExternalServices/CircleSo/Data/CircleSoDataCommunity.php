<?php

namespace App\Services\ExternalServices\CircleSo\Data;

use App\Clients\BaseEntity;

class CircleSoDataCommunity extends BaseEntity
{
    protected array $config = array('id', 'name', 'slug', 'owner_id', 'is_private', 'space_ids');
}

