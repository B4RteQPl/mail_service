<?php

namespace App\Services\ExternalServices\CircleSo\Data;

use App\Services\ExternalServices\BaseEntity;

class CircleSoDataSpaceGroup extends BaseEntity
{
    protected array $config = array('id', 'name', 'community_id', 'automatically_add_members_to_new_spaces', 'add_members_to_space_group_on_space_join', 'slug');
}

