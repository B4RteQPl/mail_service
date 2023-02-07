<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class CannotGetSubscriberException extends Exception
{

    public function report()
    {
        info('Cannot get subscriber');
    }

}
