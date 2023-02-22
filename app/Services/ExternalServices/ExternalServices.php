<?php

namespace App\Services\ExternalServices;

use App\Services\ExternalServices\ActiveCampaign\ActiveCampaign;
use App\Services\ExternalServices\CircleSo\CircleSo;
use App\Services\ExternalServices\ConvertKit\ConvertKit;
use App\Services\ExternalServices\GetResponse\GetResponse;
use App\Services\ExternalServices\Mailchimp\Mailchimp;
use App\Services\ExternalServices\MailerLite\MailerLite;
use App\Services\ExternalServices\MailerLiteClassic\MailerLiteClassic;
use App\Services\ExternalServices\Sendgrid\Sendgrid;

class ExternalServices extends AbstractServiceLoader
{
    // add more services here in following convention (class => key to use in config e.g. $config->convertKit)
    const ACTIVE_SERVICES = [
        ActiveCampaign::class => 'activeCampaign',
        CircleSo::class => 'circleSo',
        ConvertKit::class => 'convertKit',
        GetResponse::class => 'getResponse',
        Mailchimp::class => 'mailchimp',
        MailerLite::class => 'mailerLite',
        MailerLiteClassic::class => 'mailerLiteClassic',
        Sendgrid::class => 'sendgrid',
    ];
}
