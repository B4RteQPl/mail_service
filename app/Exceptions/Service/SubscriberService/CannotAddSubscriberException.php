<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotAddSubscriberException extends Exception
{

    public function report()
    {
        info('Cannot add new subscriber');
    }

}
