<?php

namespace Tests\Feature\Services\ExternalServices\ConvertKit\Client;

use Tests\Feature\Services\ExternalServices\ConvertKit\ConvertKitTestCase;

class ConvertKitIsConnectionOkTest extends ConvertKitTestCase
{

    /**
     * @test
     */
    public function when_authorization_ok_then_return_true()
    {
        $this->assertTrue($this->convertKit()->client->isConnectionOk());
    }

    /**
     * @test
     */
    public function when_authorization_failed_then_return_false()
    {
        $this->assertFalse($this->externalServices()->convertKit->setClient('invalid')->client->isConnectionOk());
    }
}
