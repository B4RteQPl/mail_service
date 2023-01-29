<?php

namespace App\Exceptions\Service\MailService;

use Exception;

class CannotAddSubscriberToGroupException extends Exception
{

    public function report()
    {
        info('MailService: Cannot add subscriber to mailing group');
    }

}
