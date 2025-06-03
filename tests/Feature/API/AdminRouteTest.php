<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated user can access analytics index', function () {
    $response = $this->actingAs($this->user)
        ->get('/admin/analytics');

    $response->assertStatus(200);
    $response->assertInertia(fn ($assert) => $assert->component('Admin/Analytics/Dashboard'));
});

test('authenticated user can export analytics data', function () {
    $response = $this->actingAs($this->user)
        ->get('/admin/analytics/export');

    $response->assertStatus(200);
    $response->assertDownload();
});

test('authenticated user can access activity logs index', function () {
    $response = $this->actingAs($this->user)
        ->get('/admin/activity-logs');

    $response->assertStatus(200);
    $response->assertInertia(fn ($assert) => $assert->component('Admin/Analytics/Index'));
});

test('authenticated user can export activity logs', function () {
    $response = $this->actingAs($this->user)
        ->get('/admin/activity-logs/export');

    $response->assertStatus(200);
    $response->assertDownload();
});
