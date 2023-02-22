<?php

namespace App\Interfaces\Services\SubscriberManager\SubscriberServices\ChannelServices;

interface ChannelDeliveryServiceInterface
{
    public function __construct(string $apiKey, ?string $apiUrl);

    public function isConnectionOk(): bool;
}
