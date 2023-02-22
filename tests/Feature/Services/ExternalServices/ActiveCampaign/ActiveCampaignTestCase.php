<?php

namespace Tests\Feature\Services\ExternalServices\ActiveCampaign;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class ActiveCampaignTestCase extends ExternalServicesTestCase
{
    public function activeCampaign()
    {
        return $this->externalServices()->activeCampaign->setClient(env('TEST_ACTIVECAMPAIGN_API_KEY'), env('TEST_ACTIVECAMPAIGN_API_URL'));
    }

    protected function deleteContact(string $contactId)
    {
        $isDeleted = $this->activeCampaign()->client->deleteContact($contactId);
        $this->assertTrue($isDeleted);
    }

}
