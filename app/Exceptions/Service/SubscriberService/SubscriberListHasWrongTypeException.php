<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class SubscriberListHasWrongTypeException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Subscriber list has a wrong type', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
