<?php

use App\Http\Controllers\Rider\AssignmentController;
use App\Http\Controllers\Rider\AttendanceController;
use App\Http\Controllers\Rider\DashboardController;
use App\Http\Controllers\Rider\ParcelCountController;
use App\Http\Controllers\Rider\PerformanceController;
use App\Http\Controllers\Rider\ProfileController;
use Illuminate\Support\Facades\Route;

// ////////////Rider Portal//////////////////
// Note: the admin panel already owns the "/rider" URIs via Route::resource('rider'),
// so the portal lives under "/rider-portal". Route names stay under "rider." —
// none of them collide with the admin resource's index/create/show/edit/... names.
// Riders sign in at /login like everyone else — see routes/auth.php.
Route::prefix('rider-portal')->name('rider.')->group(function () {
    Route::middleware('auth:rider')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');

        Route::get('assignment', [AssignmentController::class, 'index'])->name('assignment.index');
        Route::get('performance', [PerformanceController::class, 'index'])->name('performance.index');

        Route::resource('parcel', ParcelCountController::class)->only(['index', 'create', 'store', 'edit', 'update']);

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
