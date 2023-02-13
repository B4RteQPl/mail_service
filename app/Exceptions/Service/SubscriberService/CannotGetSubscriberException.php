<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotGetSubscriberException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Cannot get subscriber', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
