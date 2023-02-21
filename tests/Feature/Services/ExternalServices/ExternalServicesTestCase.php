<?php

namespace Tests\Feature\Services\ExternalServices;

use App\Services\ExternalServices\ExternalServices;
use Tests\TestCase;

abstract class ExternalServicesTestCase extends TestCase
{

    public function externalServices()
    {
        return new ExternalServices();
    }
}
