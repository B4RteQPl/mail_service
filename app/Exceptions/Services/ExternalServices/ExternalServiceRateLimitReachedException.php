<?php

namespace App\Exceptions\Services\ExternalServices;

use Exception;

class ExternalServiceRateLimitReachedException extends ExternalServiceException
{
    public function __construct(array $debugData = [], string $message = 'Provider rate limit has been reached', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
