<?php

namespace Tests;

abstract class CommandTestCase extends TestCase
{
    use CreatesApplication;

    public function assertConfigRequiredFields(array $config)
    {
        $this->assertArrayHasKey('actionName', $config);
    }

    public function assertConfigFields(array $config, array $fields)
    {
        $this->assertArrayHasKey('fields', $config);

        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $config['fields']);
        }
    }

    public function getNewUser(): array
    {
        return [
            'email' => $this->getUniqueEmail(),
            'firstName' => 'John',
            'lastName' => 'Snow',
        ];
    }

    //    public function assertResultContains(array $result, array $expected)
    //    {
    //        foreach ($expected as $key => $value) {
    //            $this->assertArrayHasKey($key, $result);
    //        }
    //    }
}
