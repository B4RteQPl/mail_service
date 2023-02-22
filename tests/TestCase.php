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
