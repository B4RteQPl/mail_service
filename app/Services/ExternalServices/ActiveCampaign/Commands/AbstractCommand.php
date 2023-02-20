<?php

namespace App\Services\ExternalServices\ActiveCampaign\Commands;


use App\Services\ExternalServices\ActiveCampaign\Client\ActiveCampaignClientInterface;
use Exception;
use Illuminate\Support\Facades\Log as Log;

abstract class AbstractCommand
{
    public function __construct(ActiveCampaignClientInterface $client)
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
