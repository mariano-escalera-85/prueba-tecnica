<?php

namespace App\Http\Controllers;

use App\DataTables\EstadosDataTable;
use App\Http\Integrations\GetEstadosRequest;
use App\Models\Estado;
use Illuminate\Http\JsonResponse;

class EstadosController extends Controller
{
    public function index(EstadosDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    /**
     * Obtiene los estados del Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.
     * Los Actualiza o Crea en la Base de Datos
     * Devuelve el conteo total de registros recientemente creados
     */
    public function fetch(
        GetEstadosRequest $getEstadosRequest,
        Estado $estado
    ): JsonResponse
    {
        if(!$estado->newQuery()->exists()){
            $estados = $getEstadosRequest->send();

            $createdCount = $estados
                ->dtoOrFail()
                ->map(fn ($estadoData)
                    => $estado->newQuery()->updateOrCreate(
                        $estadoData->only(['cve_ent', 'nomgeo']),
                        $estadoData->except(['cve_ent', 'nomgeo'])
                    )
                )
                ->filter(fn ($estado) => $estado->wasRecentlyCreated)
                ->count();
        }

        return response()->json(['count' => $createdCount ?? 0], 201);
    }

    /**
     * Remueve todos los estados con sus correspondientes municipios (por medio de `Cascade on Delete` de la DB)
     */
    public function destroy(Estado $estado): JsonResponse
    {
        $deletedCound = $estado->newQuery()->delete();

        return response()->json(['count' => $deletedCound], 202);
    }
}
