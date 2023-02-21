<?php

namespace App\Services\ExternalServices\MailerLiteClassic\Data;

use App\Services\ExternalServices\BaseEntity;

class MailerLiteClassicDataSubscriber extends BaseEntity
{
    protected array $config = array('id', 'name', 'email', 'groups');
}

