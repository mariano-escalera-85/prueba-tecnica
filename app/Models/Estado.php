<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'cve_ent', 'cve_ent');
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
