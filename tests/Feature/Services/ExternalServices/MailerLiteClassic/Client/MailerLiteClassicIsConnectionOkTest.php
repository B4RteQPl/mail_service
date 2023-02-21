<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\MailerLiteClassic\MailerLiteClassicTestCase;

class MailerLiteClassicIsConnectionOkTest extends MailerLiteClassicTestCase
{

    /**
     * @test
     */
    public function when_authorization_ok_then_return_true()
    {
        $this->assertTrue($this->mailerLiteClassic()->client->isConnectionOk());
    }

    /**
     * @test
     */
    public function when_authorization_failed_then_return_false()
    {
        $this->assertFalse($this->externalServices()->mailerLiteClassic->setClient('invalid')->client->isConnectionOk());
    }
}
