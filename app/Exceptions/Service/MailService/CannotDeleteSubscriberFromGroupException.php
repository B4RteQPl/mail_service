<?php

namespace App\Exceptions\Service\MailService;

use Exception;

class CannotDeleteSubscriberFromGroupException extends Exception
{

    public function report()
    {
        info('MailService: Cannot delete subscriber from mailing group');
    }

}
