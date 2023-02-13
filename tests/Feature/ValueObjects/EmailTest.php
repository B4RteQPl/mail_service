<?php

namespace Tests\Feature\ValueObjects;

use App\Exceptions\ValueObjects\EmailInvalidException;
use App\ValueObjects\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{

    /**
     * @test
     */
    public function when_email_is_invalid_then_exception_is_thrown()
    {
        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Invalid email address');

        new Email('invalid');
    }

    /**
     * @test
     */
    public function when_email_is_valid_then_can_get_email()
    {
        $value = 'example@example.com';
        $email = new Email($value);

        $this->assertEquals($email->get(), $value);
        $this->assertEquals((string) $email, $value);
    }

    /**
     * @test
     */
    public function when_email_is_valid_then_can_set_email()
    {
        $newValue = 'next@example.com';
        $email = new Email('example@example.com');

        $email->set($newValue);

        $this->assertEquals($newValue, $email->get());
        $this->assertEquals($newValue, (string) $email);
    }

    /**
     * @test
     */
    public function when_email_is_changed_to_invalid_then_throw_exception()
    {
        $email = new Email('example@example.com');

        $this->expectException(EmailInvalidException::class);
        $this->expectExceptionMessage('Invalid email address');

        $email->set('invalid');
    }
}
