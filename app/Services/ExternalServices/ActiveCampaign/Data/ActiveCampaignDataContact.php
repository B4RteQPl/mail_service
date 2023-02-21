<?php

namespace App\Services\ExternalServices\ActiveCampaign\Data;

use App\Services\ExternalServices\BaseEntity;

class ActiveCampaignDataContact extends BaseEntity
{
    protected array $config = array('id', 'email', 'firstName', 'lastName');
}

