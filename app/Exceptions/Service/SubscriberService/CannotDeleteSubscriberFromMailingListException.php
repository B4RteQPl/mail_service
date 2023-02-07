<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;
use Illuminate\Support\Facades\Log;

class CannotDeleteSubscriberFromMailingListException extends Exception
{

    private array $debugData;

    public function __construct(string $message = 'Cannot delete subscriber, because is not assigned to mailing list', array $debugData = [], $code = 0, Exception $previous = null)
    {
        $this->debugData = $debugData;
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::error($this->getMessage(), $this->debugData);
    }

}
