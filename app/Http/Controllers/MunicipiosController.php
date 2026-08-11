<?php

namespace App\Http\Controllers;

use App\DataTables\MunicipiosDataTable;
use App\Http\Integrations\GetMunicipiosRequest;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MunicipiosController extends Controller
{
    /**
     * Obtiene todos los Municipios correspondientes al Estado del Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.
     * Los actualiza o crea en la Base de Datos
     * Los devuelve como una respuesta AJAX de DataTables
     */
    public function __invoke(
        Estado $estado,
        Municipio $municipio,
        GetMunicipiosRequest $getMunicipiosRequest,
        MunicipiosDataTable $dataTable
    ): JsonResponse
    {
        Cache::remember("municipios.{$estado->cve_ent}", 3600, function () use ($estado, $municipio, $getMunicipiosRequest) {
            $municipios = $getMunicipiosRequest->send();

            Log::info("Caching municipios API request for Estado: {$estado->nomgeo} ({$estado->cve_ent})");

            return $municipios->dtoOrFail()
                ->map(fn ($municipioData)
                    => $municipio->newQuery()->updateOrCreate(
                        $municipioData->only(['cve_ent', 'cve_mun', 'nomgeo']),
                        $municipioData->except(['cve_ent', 'cve_mun', 'nomgeo'])
                    )
                );
        });

        return $dataTable->with('estado', $estado)->ajax();
    }
}
