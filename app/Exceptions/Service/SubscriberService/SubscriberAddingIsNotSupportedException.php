<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class SubscriberAddingIsNotSupportedException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Cannot use add subscriber method', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
