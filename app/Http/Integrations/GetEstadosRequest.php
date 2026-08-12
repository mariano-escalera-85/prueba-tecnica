<?php

namespace App\Http\Integrations;

use App\Dtos\EstadoData;
use App\Http\Integrations\Contracts\ValidatesResponse;
use App\Http\Integrations\Middlewares\ValidateResponseData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Http\SoloRequest;

class GetEstadosRequest extends SoloRequest implements Cacheable, ValidatesResponse
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(ValidateResponseData $responseValidator)
    {
        $this->middleware()->onResponse($responseValidator);
    }

    public function resolveEndpoint(): string
    {
        return config('gaia.estados.endpoint');
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

        return collect($datos)->map(fn ($data) => EstadoData::from($data));
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store());
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
            'datos.*.nomgeo' => 'required',
            'datos.*.nom_abrev' => 'sometimes',
            'datos.*.pob_total' => 'required|numeric',
            'datos.*.pob_femenina' => 'sometimes|numeric',
            'datos.*.pob_masculina' => 'sometimes|numeric',
            'datos.*.total_viviendas_habitadas' => 'sometimes|numeric',
            'numReg' => 'sometimes|numeric',
        ];
    }
}
