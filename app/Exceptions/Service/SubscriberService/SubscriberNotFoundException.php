<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class SubscriberNotFoundException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Subscriber not found', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
