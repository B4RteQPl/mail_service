<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid;

use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class SendgridTestCase extends ExternalServicesTestCase
{
    public function sendgrid()
    {
        return $this->externalServices()->sendgrid->setClient(env('TEST_SENDGRID_API_KEY'));
    }
}
