<?php

namespace Tests\Feature\Services\ExternalServices\CircleSo;

use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class CircleSoTestCase extends ExternalServicesTestCase
{
    public function circleSo()
    {
        return $this->externalServices()->circleSo->setClient(env('TEST_CIRCLE_SO_API_KEY'));
    }
}
