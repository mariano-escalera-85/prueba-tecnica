<?php

namespace App\Dtos;

class MunicipioData extends Data
{
    public function __construct(
        public readonly string $cvegeo,
        public readonly string $cve_ent,
        public readonly string $cve_mun,
        public readonly string $nomgeo,
        public readonly string $cve_cab,
        public readonly string|null $nom_cab = null,
        public readonly int|null $pob_total = null,
        public readonly int|null $pob_femenina = null,
        public readonly int|null $pob_masculina = null,
        public readonly int|null $total_viviendas_habitadas = null,
    ){}
}
