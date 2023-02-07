<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;
use Illuminate\Support\Facades\Log;

class SubscriberAddingIsNotSupportedException extends Exception
{

    private array $debugData;

    public function __construct(string $message = 'Cannot use add subscriber method', array $debugData = [], $code = 0, Exception $previous = null)
    {
        $this->debugData = $debugData;
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::info($this->getMessage(), $this->debugData);
    }

}
