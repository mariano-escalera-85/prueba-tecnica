<?php

namespace App\Http\Integrations\Middlewares;

use Illuminate\Support\Facades\Log;
use Saloon\Contracts\ResponseMiddleware;
use Saloon\Http\Response;

class LogResponse implements ResponseMiddleware
{
    public function __invoke(Response $response): void
    {
        $request = $response->getPsrRequest();

        $status = (int) ($response->json('result') ?? $response->status());
        $message = $response->json('mensaje') ?? $response->body();
        $url = (string) $request->getUri();

        Log::error(
            'API Request have errors:' . get_class($request),
            [
                'url' => $url,
                'status' => $status,
                'message' => $message,
            ]
        );
    }
}
