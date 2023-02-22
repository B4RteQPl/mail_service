<?php

namespace App\Services\ExternalServices\GetResponse\Commands;


use App\Services\ExternalServices\GetResponse\Client\GetResponseClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(GetResponseClientInterface $client)
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
