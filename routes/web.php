<?php

use App\Http\Controllers\DomainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('domains', [DomainController::class, 'index'])
        ->name('domains.index');

    Route::get('vehicles', [DomainController::class, 'vehicle'])
        ->name('vehicles.index');

    Route::get('vehicles/search', [DomainController::class, 'advanceSearch'])
        ->name('vehicles.search');
});

Route::get('/images/{filename}', function ($filename) {
    $path = resource_path("images/{$filename}");

    if (file_exists($path)) {
        return Response::file($path);
    }

    abort(404);
});


require __DIR__.'/auth.php';
