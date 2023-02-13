<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotDeleteSubscriberFromMailingListException extends SubscriberBaseException
{
    public function __construct(array $debugData = [], string $message = 'Cannot delete subscriber, because is not assigned to mailing list', $code = 0, Exception $previous = null)
    {
        parent::__construct($debugData, $message, $code, $previous);
    }
}
