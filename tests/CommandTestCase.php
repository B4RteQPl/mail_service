<?php

namespace Tests;

abstract class CommandTestCase extends TestCase
{
    use CreatesApplication;

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
