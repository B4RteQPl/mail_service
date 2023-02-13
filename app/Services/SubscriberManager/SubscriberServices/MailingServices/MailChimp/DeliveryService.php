<?php

namespace App\Services\SubscriberManager\SubscriberServices\MailingServices\MailChimp;
use App\Exceptions\Service\SubscriberService\CannotAddSubscriberToMailingListException;
use App\Exceptions\Service\SubscriberService\CannotDeleteSubscriberFromMailingListException;
use App\Exceptions\Service\SubscriberService\SubscriberAddingIsNotSupportedException;
use App\Exceptions\Service\SubscriberService\SubscriberNotFoundException;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\SubscriberManager\SubscriberServices\MailingServices\MailDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\BaseDeliveryService;

class DeliveryService extends BaseDeliveryService implements MailDeliveryServiceInterface
{
    const TYPE = 'MAILCHIMP';
    protected string $type = self::TYPE;

    protected string $testGroupId = 'TEST_MAILCHIMP_GROUP_ID';
    protected string $testSecondGroupId = 'TEST_MAILCHIMP_SECOND_GROUP_ID';
    protected $mailchimp;

    public function __construct(string $authKey, string $apiUrl = null)
    {
        parent::__construct($authKey, $apiUrl);

        $this->mailchimp = new \MailchimpMarketing\ApiClient();

        $serverPrefix = $this->getServerPrefix($authKey);
        $this->mailchimp->setConfig([
            'apiKey' => $authKey,
            'server' =>$serverPrefix
        ]);
    }

    public function isConnectionOk(): bool
    {
        try {
            $response = $this->mailchimp->ping->get();
        } catch (\Exception $e) {
            return false;
        }

        return $response->health_status === 'Everything\'s Chimpy!';
    }

    /**
     * @return SubscriberListInterface[]
     *
     * @url https://mailchimp.com/developer/marketing/api/list-member-tags/list-member-tags/
     */
    public function getSubscriberLists(): array
    {
        // Mailchimp in free plan allows to create only single audience list
        $response = $this->mailchimp->lists->getAllLists();

         return Responder::for($response)->getSubscriberLists();
    }

    /**
     * @throws SubscriberAddingIsNotSupportedException
     */
    public function addSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        throw new SubscriberAddingIsNotSupportedException(
            ['subscriber' => $subscriber],
            'Mailchimp requires list id to add subscriber'
        );
    }

    /**
     * @throws SubscriberNotFoundException
     *
     * @url https://mailchimp.com/developer/marketing/api/list-members/get-member-info/
     */
    public function verifySubscriber(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList = null): SubscriberInterface
    {
        try {
            $subscriberHash = md5(strtolower($subscriber->email->get()));
            $response = $this->mailchimp->lists->getListMember($subscriberList->id, $subscriberHash);

            return Responder::for($response)->updateSubscriberFromSearchResult($subscriber);
        } catch (\Exception $e) {
            throw new SubscriberNotFoundException([], 'Subscriber Not Found');
        }
    }

    /**
     * @throws CannotAddSubscriberToMailingListException
     *
     * @url https://mailchimp.com/developer/marketing/api/list-members/add-member-to-list/
     */
    public function addSubscriberToSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        try {
            $response = $this->mailchimp->lists->addListMember($subscriberList->id, [
                "email_address" => $subscriber->email->get(),
                "status" => "subscribed",
            ]);

            return Responder::for($response)->updateSubscriberAfterAddToSubscriberList($subscriber, $subscriberList);
        } catch (\Exception $e) {
            throw new CannotAddSubscriberToMailingListException([], 'Something went wrong');
        }
    }

    /**
     * @throws CannotDeleteSubscriberFromMailingListException
     *
     * @url https://mailchimp.com/developer/marketing/api/list-members/archive-list-member/
     *
     * could be replaced with https://mailchimp.com/developer/marketing/api/list-members/delete-list-member/
     */
    public function deleteSubscriberFromSubscriberList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        try {
            $subscriberHash = md5(strtolower($subscriber->email->get()));
            $response = $this->mailchimp->lists->deleteListMember($subscriberList->id, $subscriberHash);

            return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
        } catch (\Exception $e) {
            throw new CannotDeleteSubscriberFromMailingListException([], 'Something went wrong');
        }
    }

    private function getServerPrefix(string $authKey): string
    {
        $authKey = explode('-', $authKey);

        // if exists 2nd part of auth key, then it is server prefix
        return $authKey[1] ?? '';
    }
}

