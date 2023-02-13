<?php

namespace App\Services\SubscriberManager\SubscriberServices;

use App\Exceptions\Services\SubscriberManager\CannotGetSubscriberException;
use App\Exceptions\Services\SubscriberManager\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Services\SubscriberManager\SubscriberNotFoundException;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\ChannelServices\ChannelDeliveryServiceInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\ServiceInterface;

class ChannelService implements ServiceInterface
{

    private ChannelDeliveryServiceInterface $deliveryService;

    public function __construct(ChannelDeliveryServiceInterface $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function isConnectionOk(): bool
    {
        return $this->deliveryService->isConnectionOk();
    }

    public function getSubscriberLists(): array
    {
        return $this->deliveryService->getSubscriberLists();
    }

    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        return $this->deliveryService->addSubscriber($subscriber);
    }

    public function verifySubscriber(SubscriberInterface $subscriber, ?SubscriberListInterface $subscriberList = null): SubscriberInterface
    {
        return $this->deliveryService->verifySubscriber($subscriber, $subscriberList);
    }

    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        return $this->deliveryService->addSubscriber($subscriber);


        $this->assertList($mailingList, 'Cannot add subscriber to mailing list, because mailing list type is different than mail provider type');

        try {
            $subscriber = $this->verifySubscriber($subscriber);
        } catch (SubscriberNotFoundException $e) {
            $e->report();
            try {
                $subscriber = $this->deliveryService->addSubscriber($subscriber);
            } catch (SubscriberAddingIsNotSupportedException $e) {
                return $this->deliveryService->addSubscriberDraftToSubscriberList($subscriber, $mailingList);
            }
        } catch (SubscriberAddingIsNotSupportedException $e) {
            $e->report();
            // in case of skip adding subscriber add it directly to mailing list
        } catch (\Exception $e) {
            // todo test what happens if other exceptions are thrown
            throw new CannotGetSubscriberException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $mailingList);
    }

    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        return $this->deliveryService->deleteSubscriber($subscriber);
    }
    //
    //    public function getDeliveryProvider()
    //    {
    //        return $this->deliveryService;
    //    }
}
