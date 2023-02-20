<?php

namespace App\Services\ExternalServices\Mailchimp\Client;

use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\ValueObjects\Email;

class MailchimpClient
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->mailchimp = new \MailchimpMarketing\ApiClient();

        $this->mailchimp->setConfig([
            'apiKey' => $apiKey,
            'server' =>explode('-', $apiKey)[1] ?? ''
        ]);
    }

    public function isConnectionOk(): bool
    {
        try {
            $response = $this->mailchimp->ping->get();
            return $response->health_status === 'Everything\'s Chimpy!';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @url https://mailchimp.com/developer/marketing/api/lists
     */
    public function getAllLists(): array
    {
        // Mailchimp in free plan allows to create only single audience list
        $response = $this->mailchimp->lists->getAllLists();

        return array_map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        }, $response->lists);
    }

    /**
     * @url https://mailchimp.com/developer/marketing/api/list-members/get-member-info/
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getListMemberInfo(Email $email, string $listId)
    {
        try {
            $subscriberHash = md5(strtolower($email->get()));
            $response = $this->mailchimp->lists->getListMember($listId, $subscriberHash);

            return [
                'id' => $response->id,
                'email_address' => $response->email_address,
                'contact_id' => $response->contact_id,
                'full_name' => $response->full_name,
                'status' => $response->status,
            ];
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Mailchimp', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://mailchimp.com/developer/marketing/api/list-members/add-member-to-list/
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function addListMember(Email $email, string $listId)
    {
        try {
            $response = $this->mailchimp->lists->addListMember($listId, [
                "email_address" => $email->get(),
                "status" => "subscribed",
            ]);

            return [
                'id' => $response->id,
                'email_address' => $response->email_address,
                'contact_id' => $response->contact_id,
                'full_name' => $response->full_name,
                'status' => $response->status,
            ];
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Mailchimp', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://mailchimp.com/developer/marketing/api/list-members/archive-list-member/
     *
     * could be replaced with https://mailchimp.com/developer/marketing/api/list-members/delete-list-member/
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function deleteListMember(Email $email, string $listId)
    {
        try {
            $subscriberHash = md5(strtolower($email->get()));
            return $this->mailchimp->lists->deleteListMember($listId, $subscriberHash);
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('Mailchimp', $e->getMessage(), $e->getCode(), $e);
        }
    }
}
