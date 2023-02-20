<?php

namespace App\Services\ExternalServices\ConvertKit\Client;

use App\ValueObjects\Email;

interface ConvertKitClientInterface
{
    public function __construct(string $apiSecret);
    public function isConnectionOk(): ?bool;
    public function listTags(): ?array;
    public function listSubscribers(Email $email);
    public function tagSubscriber(Email $email, string $tagId);
    public function removeTagFromSubscriber(string $subscriberId, string $tagId);
}
