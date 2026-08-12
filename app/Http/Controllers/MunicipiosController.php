<?php

namespace App\Http\Controllers;

use App\DataTables\MunicipiosDataTable;
use App\Http\Integrations\GetMunicipiosRequest;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Http\JsonResponse;

class MunicipiosController extends Controller
{
    /**
     * Cuando el Estado no tiene ningun municipio asociado:
     *     Obtiene todos los Municipios correspondientes al Estado del Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.
     *     Los actualiza o crea en la Base de Datos
     * Devuelve una respuesta AJAX de MunicipiosDataTable
     */
    public function __invoke(
        Estado $estado,
        Municipio $municipio,
        GetMunicipiosRequest $getMunicipiosRequest,
        MunicipiosDataTable $dataTable
    ): JsonResponse
    {
        if (!$estado->municipios()->exists()) {
            $municipios = $getMunicipiosRequest->send();

            $municipios->dtoOrFail()
                ->map(fn ($municipioData)
                    => $municipio->newQuery()->updateOrCreate(
                        $municipioData->only(['cve_ent', 'cve_mun', 'nomgeo']),
                        $municipioData->except(['cve_ent', 'cve_mun', 'nomgeo'])
                    )
                );
        }

        return $dataTable->with('estado', $estado)->ajax();
    }
}
