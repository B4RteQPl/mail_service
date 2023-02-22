<?php

namespace App\Exceptions\Services\SubscriberManager;

use Exception;

class ProviderRateLimitException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Provider rate limit has been reached', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
