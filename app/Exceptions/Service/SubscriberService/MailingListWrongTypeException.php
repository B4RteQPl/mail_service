<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;
use Illuminate\Support\Facades\Log;

class MailingListWrongTypeException extends Exception
{

    private array $debugData;

    public function __construct(string $message = 'Mailing list type is different than mail provider type', array $debugData = [], $code = 0, Exception $previous = null)
    {
        $this->debugData = $debugData;
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::error($this->getMessage(), $this->debugData);
    }

}
