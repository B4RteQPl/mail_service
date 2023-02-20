<?php

namespace App\Services\ExternalServices\Sendgrid\Data;

use App\Clients\BaseEntity;

class SendgridDataList extends BaseEntity
{
    protected array $config = array('id', 'name');
}

