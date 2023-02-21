<?php

namespace App\Services\ExternalServices\GetResponse\Data;

use App\Services\ExternalServices\BaseEntity;

class GetResponseDataCampaign extends BaseEntity
{
    protected array $config = array('campaignId', 'name', 'description');
}

