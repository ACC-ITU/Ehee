<?php

use App\Models\User;
use Chumputy\Ulhandhu\Facades\Ulhandhu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    // Clear any previous mocks
    Mockery::close();
});

afterEach(function () {
    // Ensure mocks are cleared after each test
    Mockery::close();
});

test('authenticated user can view vehicles index page', function () {
    // Mock the API response
    $paginatedResponse = (object) [
        'items' => [
            (object) [
                'registrationNumber' => 'ABC123',
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
            ],
            (object) [
                'registrationNumber' => 'XYZ789',
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => 2019,
            ],
        ],
        'currentPage' => 1,
        'perPage' => 10,
        'lastPage' => 1,
        'total' => 2,
    ];

    Ulhandhu::shouldReceive('vehicle->get')
        ->once()
        ->andReturn($paginatedResponse);

    $response = $this->actingAs($this->user)
        ->get('/vehicles');

    $response->assertStatus(200);
    $response->assertInertia(fn ($assert) => $assert->component('Vehicle/Index'));
});

test('authenticated user can view vehicle details page', function () {
    // Mock the API response for specific vehicle
    $vehicleData = (object) [
        'registrationNumber' => 'ABC123',
        'make' => 'Toyota',
        'model' => 'Corolla',
        'year' => 2020,
        'color' => 'Red',
        'owner' => (object) [
            'name' => 'John Doe',
            'id' => '123456789',
        ],
    ];

    // Mock the Ulhandhu facade
    $this->mock(Ulhandhu::class, function (MockInterface $mock) use ($vehicleData) {
        $mock->shouldReceive('vehicle->find')
            ->with('ABC123')
            ->once()
            ->andReturn($vehicleData);
    });

    $response = $this->actingAs($this->user)
        ->get('/vehicles/ABC123');

    $response->assertStatus(200);
    $response->assertInertia(fn ($assert) => $assert->component('Vehicles/Show')
        ->has('vehicle')
    );
});

test('vehicle show returns 404 for non-existent vehicle', function () {
    // Mock the Ulhandhu facade to return null (vehicle not found)
    $this->mock(Ulhandhu::class, function (MockInterface $mock) {
        $mock->shouldReceive('vehicle->find')
            ->with('NONEXISTENT')
            ->once()
            ->andReturn(null);
    });

    $response = $this->actingAs($this->user)
        ->get('/vehicles/NONEXISTENT');

    $response->assertStatus(404);
});
