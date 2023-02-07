<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotAddSubscriberToMailingListException extends Exception
{

    public function report()
    {
        info('MailService: Cannot add subscriber to mailing group');
    }

}
