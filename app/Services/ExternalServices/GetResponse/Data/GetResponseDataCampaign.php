<?php

namespace App\Services\ExternalServices\GetResponse\Data;

use App\Clients\BaseEntity;

class GetResponseDataCampaign extends BaseEntity
{
    protected array $config = array('campaignId', 'name', 'description');
}

