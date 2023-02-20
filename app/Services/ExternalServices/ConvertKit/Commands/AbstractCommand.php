<?php

namespace App\Services\ExternalServices\ConvertKit\Commands;


use App\Services\ExternalServices\ConvertKit\Client\ConvertKitClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(ConvertKitClientInterface $client)
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
