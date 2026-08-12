<?php

namespace Tests\Unit\Dtos;

use App\Dtos\EstadoData;

test('EstadoData can be instantiated with required fields', function () {
    $data = new EstadoData(
        cve_ent: '01',
        nomgeo: 'Aguascalientes',
        pob_total: 1000000,
    );

    expect($data)->toBeInstanceOf(EstadoData::class)
        ->and($data->cve_ent)->toBe('01')
        ->and($data->nomgeo)->toBe('Aguascalientes')
        ->and($data->pob_total)->toBe(1000000)
        ->and($data->cvegeo)->toBeNull()
        ->and($data->nom_abrev)->toBeNull()
        ->and($data->pob_femenina)->toBeNull()
        ->and($data->pob_masculina)->toBeNull()
        ->and($data->total_viviendas_habitadas)->toBeNull();
});

test('EstadoData can be instantiated with all fields', function () {
    $data = new EstadoData(
        cve_ent: '01',
        nomgeo: 'Aguascalientes',
        pob_total: 1425607,
        cvegeo: '01',
        nom_abrev: 'Ags.',
        pob_femenina: 728924,
        pob_masculina: 696683,
        total_viviendas_habitadas: 386445,
    );

    expect($data)->toBeInstanceOf(EstadoData::class)
        ->and($data->cve_ent)->toBe('01')
        ->and($data->nomgeo)->toBe('Aguascalientes')
        ->and($data->pob_total)->toBe(1425607)
        ->and($data->cvegeo)->toBe('01')
        ->and($data->nom_abrev)->toBe('Ags.')
        ->and($data->pob_femenina)->toBe(728924)
        ->and($data->pob_masculina)->toBe(696683)
        ->and($data->total_viviendas_habitadas)->toBe(386445);
});

test('EstadoData throws exception when required fields are missing', function () {
    new EstadoData(
        nomgeo: 'Aguascalientes',
        pob_total: 1000000,
    );
})->throws(\ArgumentCountError::class);
