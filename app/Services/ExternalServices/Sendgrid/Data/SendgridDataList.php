<?php

namespace App\Services\ExternalServices\Sendgrid\Data;

use App\Services\ExternalServices\BaseEntity;

class SendgridDataList extends BaseEntity
{
    protected array $config = array('id', 'name');
}

