<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices\Discord;

class Responder
{

    protected $response;

    private function __construct($response)
    {
        $this->response = $response;
    }

    public static function for($response): self
    {
        return new self($response->json());
    }
}
