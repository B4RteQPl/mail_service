<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices;

class BaseDeliveryService
{
    protected string $testGroupId = 'TESTING_GROUP_ID';
    protected string $testSecondGroupId = 'TESTING_SECOND_GROUP_ID';
    protected string $authKey;
    protected string $apiUrl;

    public function __construct(string $authKey, string $apiUrl = null)
    {
        $this->authKey = $authKey;
        if (!empty($apiUrl)) {
            $this->apiUrl = $apiUrl;
        }
    }

    /**
     * @throws \Exception
     */
    public function getTestingGroupId(): string
    {
        if (empty(env($this->testGroupId))) {
            throw new \Exception('Missing env variable: ' . $this->testGroupId);
        }

        return env($this->testGroupId);
    }

    /**
     * @throws \Exception
     */
    public function getTestingSecondGroupId(): string
    {
        if (empty(env($this->testSecondGroupId))) {
            throw new \Exception('Missing env variable: ' . $this->testSecondGroupId);
        }

        return env($this->testSecondGroupId);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
