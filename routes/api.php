<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\ParcelCountController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\RiderAssignmentController;
use App\Http\Controllers\Api\V1\RiderAttendanceController;
use App\Http\Controllers\Api\V1\RiderController;
use App\Http\Controllers\Api\V1\RiderPerformanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('riders', RiderController::class);
        Route::apiResource('attendances', RiderAttendanceController::class);
        Route::apiResource('performances', RiderPerformanceController::class);
        Route::apiResource('assignments', RiderAssignmentController::class);
        Route::apiResource('parcel-counts', ParcelCountController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('attendance', [ReportController::class, 'attendance'])->name('attendance');
            Route::get('parcels', [ReportController::class, 'parcels'])->name('parcels');
            Route::get('performance', [ReportController::class, 'performance'])->name('performance');
            Route::get('company/{company}/overview', [ReportController::class, 'companyOverview'])->name('company-overview');
        });
    });
});
