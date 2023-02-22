<?php

namespace Tests\Feature\Services\ExternalServices\GetResponse;

use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class GetResponseTestCase extends ExternalServicesTestCase
{

    public function getResponse()
    {
        return $this->externalServices()->getResponse->setClient(env('TEST_GETRESPONSE_API_KEY'));
    }

}
