<?php

namespace App\Services\ExternalServices\ActiveCampaign\Client;

use App\Services\ExternalServices\ActiveCampaign\Data\ActiveCampaignDataList;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

interface ActiveCampaignClientInterface
{
    public function __construct(string $authKey, string $endpoint);

    /**
     * @return ?ActiveCampaignDataList[]
     */
    public function retrieveAllLists(): ?array;
    public function createNewContact(Email $email, FirstName $firstName, LastName $lastName);
    public function searchContactByEmail(Email $email);
    public function updateListStatusForContact(string $listId, string $contactId, string $status);
}
