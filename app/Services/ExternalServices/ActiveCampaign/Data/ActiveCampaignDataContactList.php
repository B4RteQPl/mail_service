<?php

namespace App\Services\ExternalServices\ActiveCampaign\Data;

use App\Clients\BaseEntity;

class ActiveCampaignDataContactList extends BaseEntity
{
    protected array $config = array('id', 'list', 'status', 'contact');
}

