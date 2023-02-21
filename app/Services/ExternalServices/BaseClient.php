<?php

namespace App\Services\ExternalServices;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log as Log;

class BaseClient
{
    protected function logException(RequestException $e): void
    {
        Log::error('ClientException', [
            'message' => $e->getMessage(),
            'response' => $e->response->json(),
        ]);
    }
}
