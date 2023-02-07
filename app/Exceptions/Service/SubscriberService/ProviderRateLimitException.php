<?php

namespace App\Exceptions\Service\SubscriberService;

use Exception;

class ProviderRateLimitException extends Exception
{

    public function report()
    {
        info('Provider rate limit has been reached');
    }

}
