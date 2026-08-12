<?php

namespace Tests\Feature\Integrations;

use App\Dtos\MunicipioData;
use App\Http\Integrations\GetMunicipiosRequest;
use App\Models\Estado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->estado = Estado::factory()->create(['cve_ent' => '01']);
    
    // We mock the Request to return the correct route parameter 'estado'
    $this->requestMock = \Mockery::mock(Request::class);
    $this->requestMock->shouldReceive('route')->with('estado')->andReturn($this->estado);
    
    $this->app->instance(Request::class, $this->requestMock);
});

test('it successfully fetches and maps municipios to dtos', function () {
    $mockData = [
        'datos' => [
            [
                'cvegeo' => '01001',
                'cve_ent' => '01',
                'cve_mun' => '001',
                'nomgeo' => 'Aguascalientes',
                'cve_cab' => '0001',
                'nom_cab' => 'Aguascalientes',
                'pob_total' => 948990,
            ],
            [
                'cvegeo' => '01002',
                'cve_ent' => '01',
                'cve_mun' => '002',
                'nomgeo' => 'Asientos',
                'cve_cab' => '0001',
                'nom_cab' => 'Asientos',
                'pob_total' => 51536,
            ]
        ],
        'numReg' => 2,
    ];

    MockClient::global([
        GetMunicipiosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $request = app(GetMunicipiosRequest::class);
    
    // Validate endpoint replacement
    expect($request->resolveEndpoint())->toContain('01');
    
    $response = $request->send();

    expect($response->status())->toBe(200);

    $dtos = $response->dtoOrFail();

    expect($dtos)->toHaveCount(2)
        ->and($dtos[0])->toBeInstanceOf(MunicipioData::class)
        ->and($dtos[0]->nomgeo)->toBe('Aguascalientes')
        ->and($dtos[1]->nomgeo)->toBe('Asientos');
});

test('it throws validation exception on invalid response data', function () {
    $mockData = [
        'datos' => [
            [
                // Missing required 'cve_ent', 'cve_mun', 'nomgeo'
                'cvegeo' => '01001',
            ]
        ]
    ];

    MockClient::global([
        GetMunicipiosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $request = app(GetMunicipiosRequest::class);
    $request->send();
})->throws(ValidationException::class);

test('it throws NotFoundException when result is not 200 even with 200 status code', function () {
    $mockData = [
        'result' => 404,
        'mensaje' => 'No records found'
    ];

    MockClient::global([
        GetMunicipiosRequest::class => MockResponse::make($mockData, 200),
    ]);

    $request = app(GetMunicipiosRequest::class);
    
    // Request will send and get a 200, but hasRequestFailed checks the body
    $response = $request->send();
    
    // We expect it to be considered failed
    expect($response->failed())->toBeTrue();
    
    // And when we throw, it should throw a NotFoundException
    $response->throw();
})->throws(NotFoundException::class);
