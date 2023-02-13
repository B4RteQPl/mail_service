<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices;

class BaseResponder
{
    protected $response;

    protected function __construct($response)
    {
        $this->response = $response;
    }
}
