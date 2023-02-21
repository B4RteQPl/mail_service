<?php

namespace Tests\Feature\Services\ExternalServices\GetResponse\Client;

use Tests\Feature\Services\ExternalServices\GetResponse\GetResponseTestCase;

class GetResponseIsConnectionOkTest extends GetResponseTestCase
{

    /**
     * @test
     */
    public function when_authorization_ok_then_return_true()
    {
        $this->assertTrue($this->getResponse()->client->isConnectionOk());
    }

    /**
     * @test
     */
    public function when_authorization_failed_then_return_false()
    {
        $this->assertFalse($this->externalServices()->getResponse->setClient('invalid')->client->isConnectionOk());
    }
}
