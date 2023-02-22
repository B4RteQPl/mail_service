<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices;

class BaseRequester
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

    public function getType(): string
    {
        return $this->type;
    }
}
