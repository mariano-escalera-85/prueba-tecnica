<?php

namespace App\DataTables;

use App\Models\Municipio;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MunicipiosDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Municipio> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->setRowId('id');
    }

    public function getColumns(): array
    {
        return [
            Column::make('cve_mun')->addClass('text-center')->title('Clave'),
            Column::make('nomgeo')->addClass('text-center')->title('Estado'),
            Column::make('pob_total')->addClass('text-center')->title('Población Total'),
        ];
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Municipio>
     */
    public function query(): QueryBuilder
    {
        return Municipio::query()
            ->select('id', 'cve_mun', 'nomgeo', 'pob_total')
            ->where('cve_ent', $this->estado->cve_ent);
    }
}
