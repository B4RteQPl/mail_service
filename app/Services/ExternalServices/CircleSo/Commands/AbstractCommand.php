<?php

namespace App\Services\ExternalServices\CircleSo\Commands;

use App\Services\ExternalServices\CircleSo\Client\CircleSoClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(CircleSoClientInterface $client)
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
