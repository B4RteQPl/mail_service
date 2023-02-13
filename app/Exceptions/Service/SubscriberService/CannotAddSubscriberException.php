<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotAddSubscriberException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Cannot add new subscriber', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
