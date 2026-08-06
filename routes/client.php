<?php

use App\Http\Controllers\Client\AssignmentController;
use App\Http\Controllers\Client\AttendanceController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ParcelAssignmentController;
use App\Http\Controllers\Client\ParcelController;
use App\Http\Controllers\Client\PerformanceController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\ReportController;
use App\Http\Controllers\Client\RiderController;
use Illuminate\Support\Facades\Route;

// ////////////Client Portal//////////////////
// Companies are the clients of the fleet. The admin panel already owns the
// "/company" URIs via Route::resource('company'), so the portal lives under
// "/client-portal". Clients sign in at /login like everyone else.
Route::prefix('client-portal')->name('client.')->middleware('auth:client')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('rider', [RiderController::class, 'index'])->name('rider.index');
    Route::get('rider/{rider}', [RiderController::class, 'show'])->name('rider.show');

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('parcel', [ParcelController::class, 'index'])->name('parcel.index');

    // Assigning a day's parcel load to one of the company's own riders.
    Route::resource('parcel-assignment', ParcelAssignmentController::class)
        ->except(['show'])
        ->parameters(['parcel-assignment' => 'assignment']);

    Route::get('assignment', [AssignmentController::class, 'index'])->name('assignment.index');
    Route::get('performance', [PerformanceController::class, 'index'])->name('performance.index');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        Route::get('attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('attendance/export', [ReportController::class, 'exportAttendanceCsv'])->name('attendance.csv');
        Route::get('attendance/export/excel', [ReportController::class, 'exportAttendanceExcel'])->name('attendance.xlsx');
        Route::get('attendance/export/pdf', [ReportController::class, 'exportAttendancePdf'])->name('attendance.pdf');

        Route::get('parcels', [ReportController::class, 'parcels'])->name('parcels');
        Route::get('parcels/export', [ReportController::class, 'exportParcelsCsv'])->name('parcels.csv');
        Route::get('parcels/export/excel', [ReportController::class, 'exportParcelsExcel'])->name('parcels.xlsx');
        Route::get('parcels/export/pdf', [ReportController::class, 'exportParcelsPdf'])->name('parcels.pdf');

        Route::get('performance', [ReportController::class, 'performance'])->name('performance');
        Route::get('performance/export', [ReportController::class, 'exportPerformanceCsv'])->name('performance.csv');
        Route::get('performance/export/excel', [ReportController::class, 'exportPerformanceExcel'])->name('performance.xlsx');
        Route::get('performance/export/pdf', [ReportController::class, 'exportPerformancePdf'])->name('performance.pdf');
    });

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
