<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LogAnalyticsController;
use App\Http\Controllers\VehicleController;
use App\Http\Middleware\CheckAdminUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use LdapRecord\Models\ActiveDirectory\User;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('vehicles', [VehicleController::class, 'index'])
        ->name('vehicles.index');

    Route::get('vehicles/{registrationNumber}', [VehicleController::class, 'show'])
        ->name('vehicles.show');
});

Route::middleware(['auth', CheckAdminUser::class])->prefix('admin')->group(function () {
    Route::get('/analytics', [LogAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [LogAnalyticsController::class, 'exportData'])->name('analytics.export');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
});


Route::get('/images/{filename}', function ($filename) {
    $path = resource_path("images/{$filename}");

    if (file_exists($path)) {
        return Response::file($path);
    }

    abort(404);
});

when(app()->isLocal(), function () {
    Route::get('/ldap', function () {
        $user = User::where('name', '=', 'Ubaidulla Ali')->first();
        $group = \LdapRecord\Models\ActiveDirectory\Group::where('cn', '=', 'Administrators')->first();;

        dd($user->getAttributes());
    });
});
require __DIR__ . '/auth.php';
