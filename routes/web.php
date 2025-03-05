<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('vehicles', [VehicleController::class, 'search'])
        ->name('vehicles.search');

    Route::get('vehicles/{registrationNumber}', [VehicleController::class, 'show'])
        ->name('vehicles.show');
});

Route::get('/images/{filename}', function ($filename) {
    $path = resource_path("images/{$filename}");

    if (file_exists($path)) {
        return Response::file($path);
    }

    abort(404);
});
require __DIR__.'/auth.php';
