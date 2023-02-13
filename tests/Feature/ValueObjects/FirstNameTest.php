<?php

namespace Tests\Feature\ValueObjects;

use App\ValueObjects\FirstName;
use Tests\TestCase;

class FirstNameTest extends TestCase
{

    /**
     * @test
     */
    public function when_value_is_valid_then_can_get_value()
    {
        $value = 'Steve';
        $firstName = new FirstName($value);

        $this->assertEquals( $value, $firstName->get(),);
        $this->assertEquals($value, (string) $firstName);
    }

    /**
     * @test
     */
    public function when_value_is_valid_then_can_set_value()
    {
        $firstName = new FirstName('John');
        $newValue = 'Steve';

        $firstName->set($newValue);

        $this->assertEquals($newValue, $firstName->get());
        $this->assertEquals($newValue, (string) $firstName);
    }
}
