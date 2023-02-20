<?php

namespace App\Services\ExternalServices\MailerLiteClassic\Commands;

use App\Services\ExternalServices\MailerLiteClassic\Client\MailerLiteClassicClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(MailerLiteClassicClientInterface $client)
    {
        $this->client = $client;
    }
    abstract public function getConfig();

    public function logException(Exception $e): void
    {
        Log::error('CommandException', [
            'message' => $e->getMessage(),
            'response' => $e->response->json(),
        ]);
    }
}
