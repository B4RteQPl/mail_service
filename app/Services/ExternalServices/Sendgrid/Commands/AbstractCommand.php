<?php

namespace App\Services\ExternalServices\Sendgrid\Commands;

use App\Services\ExternalServices\Sendgrid\Client\SendgridClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(SendgridClientInterface $client)
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
