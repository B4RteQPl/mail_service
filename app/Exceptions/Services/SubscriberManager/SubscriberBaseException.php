<?php

namespace App\Exceptions\Services\SubscriberManager;

use Exception;
use Illuminate\Support\Facades\Log as Log;

class SubscriberBaseException extends Exception
{
    protected array $debugData;

    public function __construct(array $debugData = [], string $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->debugData = $debugData;
        $this->report();
    }

    public function report()
    {
        Log::error($this->getMessage(), $this->debugData);
    }
}
