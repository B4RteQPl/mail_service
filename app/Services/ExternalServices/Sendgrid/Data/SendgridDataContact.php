<?php

namespace App\Services\ExternalServices\Sendgrid\Data;

use App\Clients\BaseEntity;

class SendgridDataContact extends BaseEntity
{
    protected array $config = array('id', 'last_name', 'first_name', 'email', 'list_ids');
}

