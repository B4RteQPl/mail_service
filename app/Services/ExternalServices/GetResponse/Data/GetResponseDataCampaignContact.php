<?php

namespace App\Services\ExternalServices\GetResponse\Data;

use App\Clients\BaseEntity;

class GetResponseDataCampaignContact extends BaseEntity
{
    protected array $config = array('contactId', 'name', 'email');
}

