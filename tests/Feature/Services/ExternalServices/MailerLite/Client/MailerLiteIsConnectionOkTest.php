<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteIsConnectionOkTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_authorization_ok_then_return_true()
    {
        $this->assertTrue($this->mailerLite()->client->isConnectionOk());
    }

    /**
     * @test
     */
    public function when_authorization_failed_then_return_false()
    {
        $this->assertFalse($this->externalServices()->mailerLite->setClient('invalid')->client->isConnectionOk());
    }
}
