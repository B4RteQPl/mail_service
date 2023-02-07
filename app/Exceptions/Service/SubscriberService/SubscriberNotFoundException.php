<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class SubscriberNotFoundException extends Exception
{

    public function report()
    {
        info('Subscriber not found');
    }

}
