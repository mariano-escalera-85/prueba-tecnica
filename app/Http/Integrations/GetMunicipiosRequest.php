<?php

namespace App\Http\Integrations;

use App\Dtos\MunicipioData;
use App\Models\Estado;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Response;
use Saloon\Http\SoloRequest;
use Throwable;

class GetMunicipiosRequest extends SoloRequest
{
    public function __construct(private Estado $estado)
    {

    }

    protected Method $method = Method::GET;

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
        $data = $response->json();

        $municipios = new Collection();

        $validator = Validator::make($data, [
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
        ]);

        return $municipios
            ->when(
                $validator->passes(),
                fn ($municipios) => $municipios->merge(Arr::get($data, 'datos')),
                fn ($municipios) => dd($validator->errors()->all())
            )
            ->map(fn ($data) => MunicipioData::from($data));
    }

    /**
     * It handles request failure when API endpoint returns Status 200 but, actually 404 in body content,
     * @param  Response $response [description]
     * @return boolean            [description]
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        return (int) ($response->json('result') ?? $response->status()) != 200;
    }

    /**
     * [getRequestException description]
     * @param  Response  $response        [description]
     * @param  Throwable $senderException [description]
     * @return [type]                     [description]
     */
    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        $status = (int) ($response->json('result') ?? $response->status());
        $message = $response->json('mensaje') ?? $response->body();

        return match ($status) {
            404 => new NotFoundException($response, $message, $status, $senderException),
            default => $senderException,
        };
    }
}
