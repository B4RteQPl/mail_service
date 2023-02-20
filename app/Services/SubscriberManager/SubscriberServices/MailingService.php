<?php

namespace App\Services\SubscriberManager\SubscriberServices;

use App\Exceptions\Services\SubscriberManager\CannotDeleteSubscriberFromSubscriberListException;
use App\Exceptions\Services\SubscriberManager\CannotGetSubscriberException;
use App\Exceptions\Services\SubscriberManager\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Services\SubscriberManager\SubscriberListNotSupportedException;
use App\Exceptions\Services\SubscriberManager\SubscriberNotFoundException;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\ServiceInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\types\MailingList;

class MailingService implements ServiceInterface
{

    private MailDeliveryServiceInterface $deliveryService;

    public function __construct(MailDeliveryServiceInterface $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function isConnectionOk(): bool
    {
        return $this->deliveryService->isConnectionOk();
    }

    /**
     * @return MailingList[]
     */
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

    /**
     * @throws \InvalidArgumentException
     * @throws CannotGetSubscriberException
     * @throws SubscriberListNotSupportedException
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $subscriberList);

        //        $this->assertSubscriberList($subscriber, $subscriberList, 'Cannot add subscriber to mailing list, because mailing list type is different than mail provider type');
        //
        //        try {
        //            $this->verifySubscriber($subscriber, $subscriberList);
        //        } catch (SubscriberNotFoundException $e) {
        //            try {
        //                $this->deliveryService->addSubscriber($subscriber);
        //            } catch (SubscriberAddingIsNotSupportedException $e) {
        //                return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $subscriberList);
        //            }
        //        } catch (SubscriberAddingIsNotSupportedException $e) {
        //            // in case of skip adding subscriber add it directly to mailing list
        //        } catch (\Exception $e) {
        //            // todo test what happens if other exceptions are thrown
        //            throw new CannotGetSubscriberException([
        //                'subscriber' => $subscriber->toArray(),
        //                'subscriberList' => $subscriberList->toArray(),
        //            ], $e->getMessage(), $e->getCode(), $e);
        //        }
        //
        //        return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $subscriberList);
    }

    /**
     * @throws CannotDeleteSubscriberFromSubscriberListException
     * @throws SubscriberListNotSupportedException
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $this->assertSubscriberList($subscriber, $subscriberList, 'Cannot delete subscriber from mailing list, because mailing list type is different than mail provider type');

        try {
            $this->deliveryService->verifySubscriber($subscriber, $subscriberList);
            if ($subscriber->mailingLists->has($subscriberList)) {
                return $this->deliveryService->deleteSubscriberFromSubscriberList($subscriber,$subscriberList);
            }
        } catch (SubscriberNotFoundException $e) {
            throw new CannotDeleteSubscriberFromSubscriberListException([
                    'subscriber' => $subscriber->toArray(),
                    'subscriberList' => $subscriberList->toArray()
                ],
                'Subscriber not found',
            );
        } catch (\Exception $e) {
            throw new CannotDeleteSubscriberFromSubscriberListException([
                'subscriber' => $subscriber->toArray(),
                'subscriberList' => $subscriberList->toArray()
            ]);
        }

        throw new CannotDeleteSubscriberFromSubscriberListException([
            'subscriber' => $subscriber->toArray(),
            'subscriberList' => $subscriberList->toArray()
        ]);
    }

    /**
     * @throws SubscriberListNotSupportedException
     */
    private function assertSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList, string $exceptionMessage = '')
    {
        if (!$subscriberList->hasType($this->deliveryService->getType())) {
            throw new SubscriberListNotSupportedException( [
                'subscriber' => $subscriber->toArray(),
                'subscriberList' => $subscriberList->toArray(),
                'deliveryService' => $this->deliveryService->getType()
            ], $exceptionMessage);
        }
    }
}
