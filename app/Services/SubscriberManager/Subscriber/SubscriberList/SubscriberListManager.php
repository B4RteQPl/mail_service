<?php

namespace App\Services\SubscriberManager\Subscriber\SubscriberList;

use App\Exceptions\Service\SubscriberService\SubscriberListNotSupportedException;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListManagerInterface;
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
     * @param SubscriberListInterface $listItem
     * @throws SubscriberListNotSupportedException
     */
    private function assertListItem(SubscriberListInterface $listItem): void
    {
        switch (true) {
            case $listItem instanceof MailingList:
                if (MailingList::isInvalid($listItem)) {
                    throw new SubscriberListNotSupportedException(['subscriberList' => $listItem]);
                }
                break;
            case $listItem instanceof ChannelList:
                if (ChannelList::isInvalid($listItem)) {
                    throw new SubscriberListNotSupportedException(['subscriberList' => $listItem]);
                }
                break;
            default:
                throw new SubscriberListNotSupportedException(['subscriberList' => $listItem]);
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
