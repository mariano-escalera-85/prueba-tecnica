<?php

namespace App\Http\Integrations\Middlewares;

use App\Http\Integrations\Contracts\ValidatesResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Saloon\Http\Response;

class ValidateResponseData
{
    public function __invoke(Response $response): void
    {
        // Skip if the request naturally failed (e.g., 404, 500)
        if ($response->failed()) {
            return;
        }

        $request = $response->getRequest();

        // If the request doesn't implement our interface, silently skip validation
        if (! $request instanceof ValidatesResponse) {
            return;
        }

        $data = $response->json();
        $rules = $request->responseRules();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            Log::error('API Response Validation Failed for ' . get_class($request), [
                'url' => (string) $response->getPsrRequest()->getUri(),
                'errors' => $errors,
            ]);

            throw new RuntimeException('API Response Validation Failed: ' . implode(', ', $errors));
        }
    }
}
