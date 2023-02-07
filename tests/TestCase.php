<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getUniqueEmail() {
        return uniqid('test-') . '@fakedomain.com';
    }
}
