<?php

namespace Tests;

use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getUniqueEmail() {
        return uniqid('test-') . '@fakedomain.com';
    }

    public function assertConfigRequiredFields(array $config)
    {
        $this->assertArrayHasKey('title', $config);
        $this->assertArrayHasKey('description', $config);
        $this->assertArrayHasKey('parameters', $config);
    }

    public function assertConfigParams(array $config, array $params)
    {
        foreach ($params as $param) {
            $this->assertArrayHasKey($param, $config['parameters']);
        }
    }

    public function getNewUser(): array
    {
        return [
            'email' => new Email($this->getUniqueEmail()),
            'firstName' => new FirstName('John'),
            'lastName' => new LastName('Snow'),
        ];
    }

    public function getCircleSoUser(string $defaultEmail = 'bartek@clearmedia.pl'): array
    {
        return [
            'communityId' => 74605,
            'email' => new Email($defaultEmail),
            'firstName' => new FirstName('John'),
            'lastName' => new LastName('Snow'),
        ];
    }
}
