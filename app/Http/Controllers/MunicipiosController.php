<?php

namespace App\Http\Controllers;

use App\DataTables\MunicipiosDataTable;
use App\Http\Integrations\GetMunicipiosRequest;
use App\Models\Estado;
use App\Models\Municipio;

class MunicipiosController extends Controller
{
    /**
     * Obtiene todos los Municipios correspondientes al Estado del Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.
     * Los actualiza o crea en la Base de Datos
     * Los devuelve como una respuesta AJAX de DataTables
     */
    public function __invoke(Estado $estado, MunicipiosDataTable $dataTable)
    {
        $municipios = new GetMunicipiosRequest($estado)->send();

        $municipios->dtoOrFail()
            ->map(fn ($municipioData)
                => Municipio::updateOrCreate(
                    $municipioData->only(['cve_ent', 'cve_mun', 'nomgeo']),
                    $municipioData->except(['cve_ent', 'cve_mun', 'nomgeo'])
                )
            );

        return $dataTable->with('estado', $estado)->ajax();
    }
}
