<?php

namespace App\Services\SubscriberManager\SubscriberServices;

use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\MailingListWrongTypeException;
use App\Exceptions\Service\SubscriberService\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\ServiceInterface;
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
     * @throws MailingListWrongTypeException
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $this->assertSubscriberList($subscriberList, 'Cannot add subscriber to mailing list, because mailing list type is different than mail provider type');

        //        if ($subscriber->isStatusVerified()) {
        //            return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $subscriberList);
        //        }

        try {
            dump(1);
            $this->verifySubscriber($subscriber, $subscriberList);
        } catch (SubscriberNotFoundException $e) {
            try {
                dump(2);
                $this->deliveryService->addSubscriber($subscriber);
            } catch (SubscriberAddingIsNotSupportedException $e) {
                dump(3);
                return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $subscriberList);
            }
        } catch (SubscriberAddingIsNotSupportedException $e) {
                dump(4);
            // in case of skip adding subscriber add it directly to mailing list
        } catch (\Exception $e) {
                dump(5);
            // todo test what happens if other exceptions are thrown
            throw new CannotGetSubscriberException([], $e->getMessage(), $e->getCode(), $e);
        }

        return $this->deliveryService->addSubscriberToSubscriberList($subscriber, $subscriberList);
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     * @throws MailingListWrongTypeException
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $this->assertSubscriberList($subscriberList, 'Cannot delete subscriber from mailing list, because mailing list type is different than mail provider type');

        try {
            $this->deliveryService->verifySubscriber($subscriber, $subscriberList);
            if ($subscriber->mailingLists->has($subscriberList)) {
                return $this->deliveryService->deleteSubscriberFromSubscriberList($subscriber,$subscriberList);
            }
        } catch (SubscriberNotFoundException $e) {
            throw new CannotDeleteSubscriberFromMailingListException(
                [
                    'subscriber' => $subscriber,
                    'subscriberList' => $subscriberList
                ],
                'Cannot delete subscriber, because not exists',
            );
        } catch (\Exception $e) {
            throw new CannotDeleteSubscriberFromMailingListException(
                [
                    'subscriber' => $subscriber,
                    'subscriberList' => $subscriberList
                ],
                'Cannot delete subscriber, because is not assigned to subscriber list',
            );
        }

        throw new CannotDeleteSubscriberFromMailingListException(
        [
            'subscriber' => $subscriber,
            'subscriberList' => $subscriberList
        ],
        'Cannot delete subscriber, something went wrong',
        );
    }

    /**
     * @throws MailingListWrongTypeException
     */
    private function assertSubscriberList(SubscriberListInterface $subscriberList, string $exceptionMessage = '')
    {
        if (!$subscriberList->hasType($this->deliveryService->getType())) {
            throw new MailingListWrongTypeException( [
                'mailingList' => $subscriberList,
                'mailType' => $this->deliveryService->getType()
            ], $exceptionMessage);
        }
    }
    //
    //        public function getProvider()
    //        {
    //            return $this->deliveryService;
    //        }
}
