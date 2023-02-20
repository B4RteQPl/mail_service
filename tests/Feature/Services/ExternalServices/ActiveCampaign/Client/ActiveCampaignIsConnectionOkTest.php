<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class ActiveCampaignIsConnectionOkTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_authorization_ok_then_return_true()
    {
        $this->assertTrue($this->activeCampaign()->client->isConnectionOk());
    }

    /**
     * @test
     */
    public function when_authorization_failed_then_return_false()
    {
        $this->assertFalse($this->externalServices()->activeCampaign->setClient('invalid', env('TEST_ACTIVECAMPAIGN_API_URL'))->client->isConnectionOk());
    }
}
