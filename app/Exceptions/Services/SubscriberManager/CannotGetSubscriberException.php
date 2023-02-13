<?php

namespace App\Exceptions\Services\SubscriberManager;

use Exception;

class CannotGetSubscriberException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Something went wrong', $code = 0, Exception
    $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
