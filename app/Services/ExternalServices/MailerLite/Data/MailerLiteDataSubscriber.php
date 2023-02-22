<?php

namespace App\Services\ExternalServices\MailerLite\Data;

use App\Services\ExternalServices\BaseEntity;

class MailerLiteDataSubscriber extends BaseEntity
{
    protected array $config = array('id', 'email', 'status', 'groups');
}

