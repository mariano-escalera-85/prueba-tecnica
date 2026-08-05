<?php

namespace App\Dtos;

class EstadoData extends Data
{
    public function __construct(
        public readonly string $cvegeo,
        public readonly string $cve_ent,
        public readonly string $nomgeo,
        public readonly string $nom_abrev,
        public readonly int $pob_total,
        public readonly int $pob_femenina,
        public readonly int $pob_masculina,
        public readonly int $total_viviendas_habitadas,
    ){}
}
