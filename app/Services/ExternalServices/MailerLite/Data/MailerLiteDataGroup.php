<?php

namespace App\Services\ExternalServices\MailerLite\Data;

use App\Clients\BaseEntity;

class MailerLiteDataGroup extends BaseEntity
{
    protected array $config = array('id', 'name');
}

