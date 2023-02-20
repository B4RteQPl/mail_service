<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList\types;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;

class CommunityList extends BaseList
{

    public static function isInvalid($list): bool
    {
        if ($list instanceof CommunityList) {
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
            if (CommunityList::isInvalid($list)) {
                return true;
            }
        }

        return false;
    }
}
