<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotAddSubscriberToMailingListException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Cannot add subscriber to mailing group', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
