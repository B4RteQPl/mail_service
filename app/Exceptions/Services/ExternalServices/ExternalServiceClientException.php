<?php

namespace App\Exceptions\Services\ExternalServices;

use Exception;
use Illuminate\Support\Facades\Log as Log;

class ExternalServiceClientException extends Exception
{
    protected string $serviceName;

    public function __construct(string $serviceName, $message = '', $code = 0, Exception $previous = null)
    {
        $this->serviceName = $serviceName;

        parent::__construct($message, $code, $previous);

        $this->report();
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function report()
    {
        Log::error($this->getMessage(), ['exception' => $this]);
    }
}
