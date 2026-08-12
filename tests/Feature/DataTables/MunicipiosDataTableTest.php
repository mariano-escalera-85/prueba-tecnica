<?php

namespace Tests\Feature\DataTables;

use App\DataTables\MunicipiosDataTable;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('MunicipiosDataTable query method returns Builder instance', function () {
    $dataTable = app(MunicipiosDataTable::class);
    $estado = \App\Models\Estado::factory()->create();
    $dataTable->with('estado', $estado);
    
    $model = new Municipio();
    
    $query = $dataTable->query($model);
    
    expect($query)->toBeInstanceOf(Builder::class);
});
