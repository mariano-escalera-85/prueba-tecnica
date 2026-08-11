<?php

namespace App\Http\Controllers;

use App\DataTables\MunicipiosDataTable;
use App\Http\Integrations\GetMunicipiosRequest;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class MunicipiosController extends Controller
{
    public function __construct(
        protected MunicipiosDataTable $dataTable,
        protected GetMunicipiosRequest $getMunicipiosRequest,
    )
    {

    }

    /**
     * Obtiene todos los Municipios correspondientes al Estado del Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.
     * Los actualiza o crea en la Base de Datos
     * Los devuelve como una respuesta AJAX de DataTables
     */
    public function __invoke(Estado $estado, Municipio $municipio): JsonResponse
    {
        Cache::remember("municipios.{$estado->cve_ent}", 3600, function () use ($municipio) {
            $municipios = $this->getMunicipiosRequest->send();

            return $municipios->dtoOrFail()
                ->map(fn ($municipioData)
                    => $municipio->newQuery()->updateOrCreate(
                        $municipioData->only(['cve_ent', 'cve_mun', 'nomgeo']),
                        $municipioData->except(['cve_ent', 'cve_mun', 'nomgeo'])
                    )
                );
        });

        return $this->dataTable->with('estado', $estado)->ajax();
    }
}
