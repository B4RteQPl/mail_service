<?php

namespace Tests\Feature\Services\ExternalServices\Traits;


use App\Services\ExternalServices\ExternalServices;

trait ExternalServicesProviderTrait
{

    public function externalServices()
    {
        return new ExternalServices();
    }

    public function activeCampaign()
    {
        return $this->externalServices()->activeCampaign->setClient(env('TEST_ACTIVECAMPAIGN_API_KEY'), env('TEST_ACTIVECAMPAIGN_API_URL'));
    }

    public function circleSo()
    {
        return $this->externalServices()->circleSo->setClient(env('TEST_CIRCLE_SO_API_KEY'));
    }

    public function convertKit()
    {
        return $this->externalServices()->convertKit->setClient(env('TEST_CONVERTKIT_API_SECRET'));
    }

    public function getResponse()
    {
        return $this->externalServices()->getResponse->setClient(env('TEST_GETRESPONSE_API_KEY'));
    }

    public function mailchimp()
    {
        return $this->externalServices()->mailchimp->setClient(env('TEST_MAILCHIMP_API_KEY'));
    }

    public function mailerLite()
    {
        return $this->externalServices()->mailerLite->setClient(env('TEST_MAILERLITE_API_KEY'));
    }

    public function mailerLiteClassic()
    {
        return $this->externalServices()->mailerLiteClassic->setClient(env('TEST_MAILERLITE_CLASSIC_API_KEY'));
    }

    public function sendgrid()
    {
        return $this->externalServices()->sendgrid->setClient(env('TEST_SENDGRID_API_KEY'));
    }

}
