<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class SubscriberListNotSupportedException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Subscriber list is not supported', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
