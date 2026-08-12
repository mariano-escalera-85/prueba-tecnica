<?php

namespace Tests\Feature\Controllers;

use App\Http\Integrations\GetMunicipiosRequest;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->estado = Estado::factory()->create(['cve_ent' => '01']);
});

test('api.estados.municipios.index returns datatables structure', function () {
    Municipio::factory()->count(3)->create(['cve_ent' => $this->estado->cve_ent]);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson(route('api.estados.municipios.index', ['estado' => $this->estado->id]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'cve_mun',
                    'nomgeo',
                    'pob_total',
                ]
            ]
        ]);
});

test('api.estados.municipios.index requires sanctum auth', function () {
    $response = $this->getJson(route('api.estados.municipios.index', ['estado' => $this->estado->id]));

    $response->assertStatus(401);
});

test('api.estados.municipios.index fetches and creates municipios if none exist for state', function () {
    $mockData = [
        'datos' => [
            [
                'cvegeo' => '01001',
                'cve_ent' => '01',
                'cve_mun' => '001',
                'nomgeo' => 'Aguascalientes',
                'cve_cab' => '0001',
            ],
            [
                'cvegeo' => '01002',
                'cve_ent' => '01',
                'cve_mun' => '002',
                'nomgeo' => 'Asientos',
                'cve_cab' => '0001',
            ]
        ],
        'numReg' => 2,
    ];

    MockClient::global([
        GetMunicipiosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $this->assertDatabaseCount('municipios', 0);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson(route('api.estados.municipios.index', ['estado' => $this->estado->id]));

    $response->assertStatus(200);

    $this->assertDatabaseCount('municipios', 2);
    $this->assertDatabaseHas('municipios', ['cve_ent' => '01', 'cve_mun' => '001', 'nomgeo' => 'Aguascalientes']);
    $this->assertDatabaseHas('municipios', ['cve_ent' => '01', 'cve_mun' => '002', 'nomgeo' => 'Asientos']);
});

test('api.estados.municipios.index does not fetch if municipios already exist for state', function () {
    Municipio::factory()->create(['cve_ent' => $this->estado->cve_ent]);

    $this->assertDatabaseCount('municipios', 1);

    MockClient::global([
        GetMunicipiosRequest::class => MockResponse::make(['error' => 'Should not be called'], 500),
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson(route('api.estados.municipios.index', ['estado' => $this->estado->id]));

    $response->assertStatus(200);

    $this->assertDatabaseCount('municipios', 1);
});
