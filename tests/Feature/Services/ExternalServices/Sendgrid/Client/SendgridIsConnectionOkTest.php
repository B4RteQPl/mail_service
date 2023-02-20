<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class SendgridIsConnectionOkTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_authorization_ok_then_return_true()
    {
        $this->assertTrue($this->sendgrid()->client->isConnectionOk());
    }

    /**
     * @test
     */
    public function when_authorization_failed_then_return_false()
    {
        $this->assertFalse($this->externalServices()->sendgrid->setClient('invalid')->client->isConnectionOk());
    }
}
