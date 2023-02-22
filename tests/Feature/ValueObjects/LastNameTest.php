<?php

namespace Tests\Feature\ValueObjects;

use App\ValueObjects\LastName;
use Tests\TestCase;

class LastNameTest extends TestCase
{

    /**
     * @test
     */
    public function when_value_is_valid_then_can_get_value()
    {
        $value = 'Jobs';
        $lastName = new LastName($value);

        $this->assertEquals( $value, $lastName->get(),);
        $this->assertEquals($value, (string) $lastName);
    }

    /**
     * @test
     */
    public function when_value_is_valid_then_can_set_value()
    {
        $lastName = new LastName('Snow');
        $newValue = 'Jobs';

        $lastName->set($newValue);

        $this->assertEquals($newValue, $lastName->get());
        $this->assertEquals($newValue, $lastName);
    }
}
