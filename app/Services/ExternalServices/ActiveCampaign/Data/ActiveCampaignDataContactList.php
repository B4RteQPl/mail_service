<?php

namespace App\Services\ExternalServices\ActiveCampaign\Data;

use App\Services\ExternalServices\BaseEntity;

class ActiveCampaignDataContactList extends BaseEntity
{
    protected array $config = array('id', 'list', 'status', 'contact');
}

