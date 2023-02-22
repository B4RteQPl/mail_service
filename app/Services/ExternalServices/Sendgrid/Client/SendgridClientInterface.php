<?php

namespace App\Services\ExternalServices\Sendgrid\Client;

use App\ValueObjects\Email;

interface SendgridClientInterface
{
    public function isConnectionOk(): bool;
    public function getAllLists(): array;
    public function addContact(Email $email);
    public function getContactsByEmail(Email $email);
    public function addContactToList(Email $email, string $listId);
    public function removeContactFromList(string $contactId, string $listId);
}
