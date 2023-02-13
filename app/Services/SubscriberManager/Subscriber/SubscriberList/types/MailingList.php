<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList\types;

use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

class MailingList extends BaseList
{

    public static function isInvalid($list): bool
    {
        if ($list instanceof MailingList) {
            return false;
        }

        return true;
    }

    /**
     * @param SubscriberListInterface[] $lists
     * @return bool
     */
    public static function isInvalidArray(array $lists): bool
    {
        foreach ($lists as $list) {
            if (MailingList::isInvalid($list)) {
                return true;
            }
        }

        return false;
    }
}
