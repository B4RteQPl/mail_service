<?php

namespace App\Services\ExternalServices\Mailchimp\Client;

use App\ValueObjects\Email;

interface MailchimpClientInterface
{
    public function __construct(string $apiKey);

    public function isConnectionOk(): bool;
    public function getListMemberTags(): array;
    public function getAllLists(): array;
    public function getListMemberInfo(Email $email, string $listId);
    public function addListMember(Email $email, string $listId);
    public function deleteListMember(Email $email, string $listId);
}
