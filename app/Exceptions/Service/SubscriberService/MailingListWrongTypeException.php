<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class MailingListWrongTypeException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Mailing list type is different than mail provider type', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
