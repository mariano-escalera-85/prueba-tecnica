<?php

namespace App\Http\Integrations;

use App\Dtos\MunicipioData;
use App\Http\Integrations\Contracts\ValidatesResponse;
use App\Http\Integrations\Middlewares\ValidateResponseData;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Response;
use Saloon\Http\SoloRequest;
use Throwable;

class GetMunicipiosRequest extends SoloRequest implements Cacheable, ValidatesResponse
{
    use HasCaching;

    protected Method $method = Method::GET;

    protected Estado $estado;

    public function __construct(
        Request $request,
        ValidateResponseData $responseValidator,
        protected LaravelCacheDriver $cacheDriver,
    )
    {
        $this->estado = $request->route('estado');

        $this->middleware()->onResponse($responseValidator);
    }

    public function resolveEndpoint(): string
    {
        $endpoint = config('services.gaia.requests.municipios');

        return str_replace('{cve_ent}', $this->estado->cve_ent, $endpoint);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        $datos = $response->json('datos');

        return collect($datos)->map(fn ($data) => MunicipioData::from($data));
    }

    /**
     * It handles request failure when API endpoint returns Status 200 but, actually 404 in body content,
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        return (int) ($response->json('result') ?? $response->status()) != 200;
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        $psrRequest = $response->getPsrRequest();
        $method = $psrRequest->getMethod();
        $url = (string) $psrRequest->getUri();

        $status = (int) ($response->json('result') ?? $response->status());
        $message = $response->json('mensaje') ?? $response->body();

        $errorMessage = sprintf("%s %s; %s", $method, $url, $message);

        return match ($status) {
            404 => new NotFoundException($response, $errorMessage, $status, $senderException),
            default => $senderException,
        };
    }

    public function resolveCacheDriver(): Driver
    {
        return $this->cacheDriver;
    }

    public function cacheExpiryInSeconds(): int
    {
        return 3600;
    }

    public function responseRules(): array
    {
        return [
            'datos'  => 'required|array',
            'datos.*.cvegeo' => 'sometimes|numeric',
            'datos.*.cve_ent' => 'required|numeric',
            'datos.*.cve_mun' => 'required|numeric',
            'datos.*.nomgeo' => 'required',
            'datos.*.cve_cab' => 'sometimes|numeric',
            'datos.*.nom_cab' => 'sometimes',
            'datos.*.pob_total' => 'sometimes|numeric',
            'datos.*.pob_femenina' => 'sometimes|numeric',
            'datos.*.pob_masculina' => 'sometimes|numeric',
            'datos.*.total_viviendas_habitadas' => 'sometimes|numeric',
            'numReg' => 'sometimes|numeric',
        ];
    }
}
