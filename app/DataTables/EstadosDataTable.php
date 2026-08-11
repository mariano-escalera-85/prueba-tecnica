<?php

namespace App\DataTables;

use App\Models\Estado;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EstadosDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Estado> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()->eloquent($query)->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Estado>
     */
    public function query(Estado $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('id', 'cve_ent', 'nomgeo', 'pob_total');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('estados-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(['cve_ent'])
            ->layout([
                'topStart' => 'buttons',
                'topEnd' => 'search',
                'bottomStart' => 'info',
                'bottomEnd' => 'paging',
            ])
            ->buttons([
                Button::raw([
                    'text'      => 'Importar Estados',
                    'className' => 'btn btn-success',
                    'action'    => 'function (e, dt, node, config) { window.runEstadosImport(dt, node); }'
                ]),
                Button::raw([
                    'text'      => 'Borrar Estados',
                    'className' => 'btn btn-error',
                    'action'    => 'function (e, dt, node, config) { window.runEstadosClear(dt, node); }'
                ]),
            ])
            ->addScript('estados.scripts.import_estados')
            ->addScript('estados.scripts.clear_estados')
            ->addScript('estados.scripts.municipios_subtable');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('cve_ent')->addClass('text-center')->title('Clave'),
            Column::make('nomgeo')->addClass('text-center')->title('Estado'),
            Column::make('pob_total')->addClass('text-center')->title('Población Total'),
            Column::computed('municipios') // Use computed for action/HTML columns
                ->defaultContent('<button class="btn btn-sm btn-info load-municipios">Municipios</button>')
                ->addClass('text-center')
                ->title('Municipios'),
        ];
    }
}
