<?php

namespace App\Services\ExternalServices\MailerLite\Commands;


use App\Services\ExternalServices\MailerLite\Client\MailerLiteClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(MailerLiteClientInterface $client)
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
