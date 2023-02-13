<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList\types;

class ChannelList extends BaseList
{

    public static function isInvalid($list): bool
    {
        if (!$list instanceof ChannelList) {
            return true;
        }

        return false;
    }

    public static function isInvalidArray(array $lists): bool
    {
        foreach ($lists as $list) {
            if (ChannelList::isInvalid($list)) {
                return true;
            }
        }

        return false;
    }
}
