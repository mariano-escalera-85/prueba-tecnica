<?php

namespace App\Http\Controllers;

use App\DataTables\EstadosDataTable;
use App\Http\Integrations\GetEstadosRequest;
use App\Models\Estado;

class EstadosController extends Controller
{
    public function index(EstadosDataTable $dataTable)
    {
        return $dataTable->render('estados.index');
    }

    /**
     * Obtiene los estados del Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.
     * Los Actualiza o Crea en la Base de Datos
     * Devuelve el conteo total de registros recientemente creados
     */
    public function fetch()
    {
        $estados = new GetEstadosRequest()->send();

        $createdCount = $estados
            ->dtoOrFail()
            ->map(fn ($estadoData)
                => Estado::updateOrCreate(
                    $estadoData->only(['cve_ent', 'nomgeo']),
                    $estadoData->except(['cve_ent', 'nomgeo'])
                )
            )
            ->filter(fn ($estado) => $estado->wasRecentlyCreated)
            ->count();

        return response()->json(['count' => $createdCount], 201);
    }

    /**
     * Remove the all of the `Estados` and its related `Municipios` at once from storage.
     * Remueve todos los estados con sus correspondientes municipios (por medio de `Cascade on Delete` de la DB)
     */
    public function destroy()
    {
        $deletedCound = Estado::query()->delete();

        return response()->json(['count' => $deletedCound], 202);
    }
}
