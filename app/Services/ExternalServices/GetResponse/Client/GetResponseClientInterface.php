<?php

namespace App\Services\ExternalServices\GetResponse\Client;

use App\ValueObjects\Email;

interface GetResponseClientInterface
{
    public function __construct(string $authKey);
    public function isConnectionOk(): bool;
    public function getCampaignList();
    public function getContactsFromCampaign(Email $email, string $campaignId);
    public function createContact(Email $email, string $campaignId);
    public function deleteContact(string $contactId);
}
