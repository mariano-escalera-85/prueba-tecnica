<?php

namespace Tests\Feature\Controllers;

use App\Http\Integrations\GetEstadosRequest;
use App\Models\Estado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('api.estados.index returns datatables structure', function () {
    Estado::factory()->count(3)->create();

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson(route('api.estados.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'id',
                    'cve_ent',
                    'nomgeo',
                    'pob_total',
                ]
            ]
        ]);
});

test('api.estados.index requires sanctum auth', function () {
    $response = $this->getJson(route('api.estados.index'));

    $response->assertStatus(401);
});

test('api.estados.fetch fetches and creates estados if none exist', function () {
    $mockData = [
        'datos' => [
            [
                'cvegeo' => '01',
                'cve_ent' => '01',
                'nomgeo' => 'Aguascalientes',
                'pob_total' => 1425607,
            ],
        ]
    ];

    MockClient::global([
        GetEstadosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $this->assertDatabaseCount('estados', 0);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson(route('api.estados.fetch'));

    $response->assertStatus(201)
        ->assertJson(['count' => 1]);

    $this->assertDatabaseCount('estados', 1);
    $this->assertDatabaseHas('estados', ['cve_ent' => '01', 'nomgeo' => 'Aguascalientes']);
});

test('api.estados.fetch returns 0 count if estados already exist', function () {
    Estado::factory()->create();

    $this->assertDatabaseCount('estados', 1);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson(route('api.estados.fetch'));

    $response->assertStatus(201)
        ->assertJson(['count' => 0]);

    $this->assertDatabaseCount('estados', 1);
});

test('api.estados.destroy removes all estados', function () {
    Estado::factory()->count(5)->create();

    $this->assertDatabaseCount('estados', 5);

    $response = $this->actingAs($this->user, 'sanctum')
        ->deleteJson(route('api.estados.destroy'));

    $response->assertStatus(202)
        ->assertJson(['count' => 5]);

    $this->assertDatabaseCount('estados', 0);
});
