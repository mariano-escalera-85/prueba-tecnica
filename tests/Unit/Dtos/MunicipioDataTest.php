<?php

namespace Tests\Unit\Dtos;

use App\Dtos\MunicipioData;

test('MunicipioData can be instantiated with required fields', function () {
    $data = new MunicipioData(
        cvegeo: '01001',
        cve_ent: '01',
        cve_mun: '001',
        nomgeo: 'Aguascalientes',
        cve_cab: '0001',
    );

    expect($data)->toBeInstanceOf(MunicipioData::class)
        ->and($data->cvegeo)->toBe('01001')
        ->and($data->cve_ent)->toBe('01')
        ->and($data->cve_mun)->toBe('001')
        ->and($data->nomgeo)->toBe('Aguascalientes')
        ->and($data->cve_cab)->toBe('0001')
        ->and($data->nom_cab)->toBeNull()
        ->and($data->pob_total)->toBeNull()
        ->and($data->pob_femenina)->toBeNull()
        ->and($data->pob_masculina)->toBeNull()
        ->and($data->total_viviendas_habitadas)->toBeNull();
});

test('MunicipioData can be instantiated with all fields', function () {
    $data = new MunicipioData(
        cvegeo: '01001',
        cve_ent: '01',
        cve_mun: '001',
        nomgeo: 'Aguascalientes',
        cve_cab: '0001',
        nom_cab: 'Aguascalientes',
        pob_total: 948990,
        pob_femenina: 486917,
        pob_masculina: 462073,
        total_viviendas_habitadas: 279532,
    );

    expect($data)->toBeInstanceOf(MunicipioData::class)
        ->and($data->cvegeo)->toBe('01001')
        ->and($data->cve_ent)->toBe('01')
        ->and($data->cve_mun)->toBe('001')
        ->and($data->nomgeo)->toBe('Aguascalientes')
        ->and($data->cve_cab)->toBe('0001')
        ->and($data->nom_cab)->toBe('Aguascalientes')
        ->and($data->pob_total)->toBe(948990)
        ->and($data->pob_femenina)->toBe(486917)
        ->and($data->pob_masculina)->toBe(462073)
        ->and($data->total_viviendas_habitadas)->toBe(279532);
});

test('MunicipioData throws exception when required fields are missing', function () {
    new MunicipioData(
        cvegeo: '01001',
        // cve_ent is missing
        cve_mun: '001',
        nomgeo: 'Aguascalientes',
        cve_cab: '0001',
    );
})->throws(\ArgumentCountError::class);
