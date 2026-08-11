<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipio extends Model
{
    protected $fillable = [
        'cvegeo',
        'cve_ent',
        "cve_mun",
        'nomgeo',
        'cve_cab',
        'nom_cab',
        'pob_total',
        'pob_femenina',
        'pob_masculina',
        'total_viviendas_habitadas',
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'cve_ent', 'cve_ent');
    }

    public function pobTotal(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value !== null
                ? number_format($value, 0, '.', ',')
                : '-',
        );
    }
}
