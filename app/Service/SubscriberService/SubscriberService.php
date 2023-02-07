<?php

namespace App\Service\SubscriberService;

use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\CannotGetSubscriberException;
use App\Exceptions\Service\SubscriberService\MailingListWrongTypeException;
use App\Exceptions\Service\SubscriberService\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Interfaces\SubscriberService\SubscriberServiceInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;

class SubscriberService implements SubscriberServiceInterface
{

    private MailProviderInterface $mailProvider;

    public function __construct(MailProviderInterface $mailProvider)
    {
        $this->mailProvider = $mailProvider;
    }

    public function isConnectionOk(): bool
    {
        return $this->mailProvider->isConnectionOk();
    }

    /**
     * @return MailingList[]
     */
    public function getMailingLists(): array
    {
        return $this->mailProvider->getMailingLists();
    }

    public function addSubscriber(SubscriberDraft $subscriber): SubscriberVerified
    {
        return $this->mailProvider->addSubscriber($subscriber);
    }

    public function getVerifiedSubscriber(SubscriberDraft|SubscriberVerified $subscriber): SubscriberVerified
    {
        return $this->mailProvider->getVerifiedSubscriber($subscriber);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws CannotGetSubscriberException
     * @throws MailingListWrongTypeException
     */
    public function addSubscriberToMailingList(SubscriberDraft|SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $this->assertMailingList($mailingList, 'Cannot add subscriber to mailing list, because mailing list type is different than mail provider type');

        try {
            $subscriber = $this->getVerifiedSubscriber($subscriber);
        } catch (SubscriberNotFoundException $e) {
            try {
                $subscriber = $this->mailProvider->addSubscriber($subscriber);
            } catch (SubscriberAddingIsNotSupportedException $e) {
                return $this->mailProvider->addSubscriberDraftToMailingList($subscriber, $mailingList);
            }
        } catch (SubscriberAddingIsNotSupportedException $e) {
            // in case of skip adding subscriber add it directly to mailing list
        } catch (\Exception $e) {
            // todo test what happens if other exceptions are thrown
            throw new CannotGetSubscriberException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->mailProvider->addSubscriberVerifiedToMailingList($subscriber, $mailingList);
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     * @throws MailingListWrongTypeException
     */
    public function deleteSubscriberFromMailingList(SubscriberDraft|SubscriberVerified $subscriber, MailingList $mailingList): SubscriberVerified
    {
        $this->assertMailingList($mailingList, 'Cannot delete subscriber from mailing list, because mailing list type is different than mail provider type');

        $subscriber = $this->mailProvider->getVerifiedSubscriber($subscriber);

        if ($subscriber->hasMailingList($mailingList)) {
            return $this->mailProvider->deleteSubscriberFromMailingList($subscriber,$mailingList);
        } else {
            throw new CannotDeleteSubscriberFromMailingListException('Cannot delete subscriber, because is not assigned to mailing list', [
                'subscriber' => $subscriber,
                'mailingList' => $mailingList
            ]);
        }
    }

    /**
     * @throws MailingListWrongTypeException
     */
    private function assertMailingList(MailingList $mailingList, string $exceptionMessage = '')
    {
        if (!$mailingList->hasMailProviderType($this->mailProvider->getMailProviderType())) {
            throw new MailingListWrongTypeException($exceptionMessage, [
                'mailingList' => $mailingList,
                'mailProviderType' => $this->mailProvider->getMailProviderType()
            ]);
        }
    }

    public function getMailProvider()
    {
        return $this->mailProvider;
    }
}
