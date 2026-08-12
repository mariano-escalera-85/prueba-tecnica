<?php

namespace Tests\Feature\Integrations;

use App\Dtos\EstadoData;
use App\Http\Integrations\GetEstadosRequest;
use Illuminate\Validation\ValidationException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('it successfully fetches and maps estados to dtos', function () {
    $mockData = [
        'datos' => [
            [
                'cvegeo' => '01',
                'cve_ent' => '01',
                'nomgeo' => 'Aguascalientes',
                'nom_abrev' => 'Ags.',
                'pob_total' => 1425607,
                'pob_femenina' => 728924,
                'pob_masculina' => 696683,
                'total_viviendas_habitadas' => 386445,
            ],
            [
                'cvegeo' => '02',
                'cve_ent' => '02',
                'nomgeo' => 'Baja California',
                'nom_abrev' => 'BC',
                'pob_total' => 3769020,
            ]
        ]
    ];

    MockClient::global([
        GetEstadosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $request = app(GetEstadosRequest::class);
    $response = $request->send();

    expect($response->status())->toBe(200);

    $dtos = $response->dtoOrFail();

    expect($dtos)->toHaveCount(2)
        ->and($dtos[0])->toBeInstanceOf(EstadoData::class)
        ->and($dtos[0]->nomgeo)->toBe('Aguascalientes')
        ->and($dtos[1]->nomgeo)->toBe('Baja California');
});

test('it throws validation exception on invalid response data', function () {
    $mockData = [
        'datos' => [
            [
                // Missing required 'cve_ent', 'nomgeo', 'pob_total'
                'cvegeo' => '01',
            ]
        ]
    ];

    MockClient::global([
        GetEstadosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $request = app(GetEstadosRequest::class);
    
    // We expect a ValidationException to be thrown by ValidateResponseData middleware
    $request->send();
})->throws(ValidationException::class);

test('it handles failed request exception', function () {
    MockClient::global([
        GetEstadosRequest::class => MockResponse::make(['error' => 'Server Error'], 500),
    ]);

    $request = app(GetEstadosRequest::class);
    $response = $request->send();

    // The middleware doesn't fail on response, but status is 500
    expect($response->status())->toBe(500)
        ->and($response->failed())->toBeTrue();
        
    $response->throw(); // This should throw a RequestException
})->throws(RequestException::class);
