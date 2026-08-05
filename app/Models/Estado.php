<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $fillable = [
        'cvegeo',
        'cve_ent',
        'nomgeo',
        'nom_abrev',
        'pob_total',
        'pob_femenina',
        'pob_masculina',
        'total_viviendas_habitadas',
    ];
}
