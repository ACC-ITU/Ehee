<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LogAnalyticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Middleware\CheckAdminUser;
use App\Http\Middleware\EnsureUserActive;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use LdapRecord\Models\ActiveDirectory\User;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::get('inactive', function () {
    if (Auth::check() && Auth::user()->isActive()) {
        return redirect()->route('vehicles.index');
    }

    return Inertia::render('Inactive');
})->name('inactive');

Route::middleware(['auth', EnsureUserActive::class])->group(function () {
    //    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('vehicles', [VehicleController::class, 'index'])
        ->name('vehicles.index');

    Route::get('vehicles/{registrationNumber}', [VehicleController::class, 'show'])
        ->name('vehicles.show');
});

Route::middleware(['auth', EnsureUserActive::class, CheckAdminUser::class])->prefix('admin')->group(function () {
    Route::get('/analytics', [LogAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [LogAnalyticsController::class, 'exportData'])->name('analytics.export');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.make-admin');
    Route::put('/users/{user}/remove-admin', [UserController::class, 'removeAdmin'])->name('users.remove-admin');
    Route::put('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::put('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
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
        $group = \LdapRecord\Models\ActiveDirectory\Group::where('cn', '=', 'Administrators')->first();

        dd($user->getAttributes());
    });
});
require __DIR__.'/auth.php';
