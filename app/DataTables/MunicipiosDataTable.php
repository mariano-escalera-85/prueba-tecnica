<?php

namespace App\DataTables;

use App\Models\Municipio;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
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
        return datatables()->eloquent($query)->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Municipio>
     */
    public function query(Municipio $municipio): QueryBuilder
    {
        return $municipio->newQuery()
            ->select('id', 'cve_mun', 'nomgeo', 'pob_total')
            ->where('cve_ent', $this->estado->cve_ent);
    }
}
