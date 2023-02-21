<?php

namespace App\Services\ExternalServices\ConvertKit\Data;

use App\Services\ExternalServices\BaseEntity;

class ConvertKitDataSubscriber extends BaseEntity
{
    protected array $config = array(
        'id',
        'email_address',
        'first_name'
    );
}

