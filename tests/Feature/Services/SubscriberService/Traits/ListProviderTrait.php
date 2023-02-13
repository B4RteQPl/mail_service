<?php

namespace Tests\Feature\Services\SubscriberService\Traits;

use App\Services\SubscriberManager\Subscriber\SubscriberList\types\ChannelList;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;

trait ListProviderTrait
{

    /**
     * @return array[]
     */
    public function ListClassProvider(): array
    {
        return [
            'Mailing List' => [MailingList::class],
            'Channel List' => [ChannelList::class],
        ];
    }
}
