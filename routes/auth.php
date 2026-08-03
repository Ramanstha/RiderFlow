<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// One front door for every portal. The guard list means an already signed-in
// admin, rider or client is bounced to their own dashboard instead of being
// shown the form again.
Route::middleware('guest:web,rider,client')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

// One exit too — it clears whichever guard is currently active.
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

// The portals used to have their own login screens. Keep the old URLs alive so
// bookmarks and any stale links land on the unified form.
Route::redirect('rider-portal/login', '/login')->name('rider.login');
Route::redirect('client-portal/login', '/login')->name('client.login');
