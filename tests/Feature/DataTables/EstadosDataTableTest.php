<?php

namespace Tests\Feature\DataTables;

use App\DataTables\EstadosDataTable;
use App\Models\Estado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('EstadosDataTable query method returns Builder instance', function () {
    $dataTable = app(EstadosDataTable::class);
    $model = new Estado();
    
    $query = $dataTable->query($model);
    
    expect($query)->toBeInstanceOf(Builder::class);
});

test('EstadosDataTable html method returns HtmlBuilder', function () {
    $dataTable = app(EstadosDataTable::class);
    
    $html = $dataTable->html();

    expect($html)->toBeInstanceOf(\Yajra\DataTables\Html\Builder::class)
        ->and($html->getTableId())->toBe('estados-table');
});

test('EstadosDataTable getColumns returns array of columns', function () {
    $dataTable = app(EstadosDataTable::class);
    
    $columns = $dataTable->getColumns();
    expect($columns)->toBeArray()
        ->and($columns)->each->toBeInstanceOf(\Yajra\DataTables\Html\Column::class);
        
    $columnNames = array_map(fn ($column) => $column->name, $columns);
    
    expect($columnNames)
        ->toContain('cve_ent')
        ->toContain('nomgeo')
        ->toContain('pob_total');
});
