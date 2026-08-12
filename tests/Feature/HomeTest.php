<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page requires authentication', function () {
    $response = $this->get('/home');

    $response->assertRedirect('/login');
});

test('home page is accessible for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/home');

    $response->assertStatus(200)
        ->assertViewIs('home')
        ->assertSee('Lista de Estados de la Republica Mexicana');
});
