<?php

namespace App\Exceptions\ValueObjects;

use Exception;
use Illuminate\Support\Facades\Log as Log;

class EmailInvalidException extends Exception
{
    protected array $debugData;

    public function __construct(array $debugData = [], string $message = 'Invalid email address', $code = 0, Exception $previous = null)
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
