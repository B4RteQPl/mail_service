<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList;

use App\Exceptions\Services\SubscriberManager\SubscriberListNotSupportedException;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManagerInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\ChannelList;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;

class SubscriberListManager implements SubscriberListManagerInterface
{

    /**
     * @var SubscriberListInterface[]
     */
    protected array $lists;

    /**
     * @param SubscriberListInterface[] $lists
     * @throws SubscriberListNotSupportedException
     */
    public function __construct(array $lists = [])
    {
        $this->set($lists);
    }

    /**
     * @param SubscriberListInterface[] $lists
     * @throws SubscriberListNotSupportedException
     */
    public function set(array $lists): void
    {
        $this->assertLists($lists);

        $this->lists = $lists;
    }

    public function get(): array
    {
        return $this->lists;
    }

    public function add(SubscriberListInterface $listToAdd): void
    {
        if ($this->has($listToAdd)) {
            return;
        }

        $this->lists[] = $listToAdd;
    }

    public function delete(SubscriberListInterface $listToDelete): void
    {
        $this->lists = array_filter($this->lists, function (SubscriberListInterface $listItem) use ($listToDelete) {
            return $listItem->id !== $listToDelete->id;
        });
    }

    public function has(SubscriberListInterface $listToVerify): bool
    {
        foreach ($this->lists as $listsItem) {
            if ($listsItem->id === $listToVerify->id) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return empty($this->lists);
    }

    /**
     * @param SubscriberListInterface[] $list
     * @throws SubscriberListNotSupportedException
     */
    private function assertLists(array $list): void
    {
        foreach ($list as $listItem) {
            $this->assertListItem($listItem);
        }
    }

    /**
     * @param SubscriberListInterface $subscriberList
     * @throws SubscriberListNotSupportedException
     */
    private function assertListItem(SubscriberListInterface $subscriberList): void
    {
        switch (true) {
            case $subscriberList instanceof MailingList:
                if (MailingList::isInvalid($subscriberList)) {
                    throw new SubscriberListNotSupportedException([
                            'subscriberList' => $subscriberList
                        ],
                        'SubscriberList of type Mailinglist is invalid'
                    );
                }
                break;
            case $subscriberList instanceof ChannelList:
                if (ChannelList::isInvalid($subscriberList)) {
                    throw new SubscriberListNotSupportedException([
                            'subscriberList' => $subscriberList
                        ],
                        'SubscriberList of type ChannelList is invalid'
                    );
                }
                break;
            default:
                throw new SubscriberListNotSupportedException([
                        'subscriberList' => $subscriberList
                    ],
                    'Subscriber list is not supported'
                );
        }
    }

    public function toArray(): array
    {
        $allLists = [];

        foreach ($this->lists as $list) {
            $allLists[] = $list->toArray();
        }

        return $allLists;
    }
}
