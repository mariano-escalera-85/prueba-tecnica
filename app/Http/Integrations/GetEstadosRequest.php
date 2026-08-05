<?php

namespace App\Http\Integrations;

use App\Dtos\EstadoData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Http\SoloRequest;

class GetEstadosRequest extends SoloRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return config('services.gaia.requests.estados');
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
        $data = $response->json();

        $estados = new Collection();

        $validator = Validator::make($data, [
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
        ]);

        return $estados
            ->when($validator->passes(), fn ($estados) => $estados->merge(Arr::get($data, 'datos'))
            ->map(fn ($data) => EstadoData::from($data)));
    }
}
