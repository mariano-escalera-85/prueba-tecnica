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
     * Store a newly created resource in storage.
     */
    public function store()
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

        return ['count' => $createdCount];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAll()
    {
        $deletedCound = Estado::query()->delete();

        return ['count' => $deletedCound];
    }
}
