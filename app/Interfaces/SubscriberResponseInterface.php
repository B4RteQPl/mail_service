<?php

namespace App\Interfaces;

use App\Service\SubscriberService\Subscriber;

interface SubscriberResponseInterface
{
    static function convert($response, Subscriber $subscriber): array;
}
