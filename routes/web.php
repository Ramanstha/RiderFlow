<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParcelCountController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RiderAssignmentController;
use App\Http\Controllers\Admin\RiderAttendanceController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\Admin\RiderPerformanceController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UI\HomeController;
use Illuminate\Support\Facades\Route;

// ////////////////Frontend/////////////////////
Route::get('/', [HomeController::class, 'Home'])->name('Home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.store');

// ////////////Admin//////////////////
Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rider', RiderController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('attendance', RiderAttendanceController::class);
    Route::resource('performance', RiderPerformanceController::class);
    Route::resource('assignment', RiderAssignmentController::class);
    Route::resource('parcel', ParcelCountController::class);
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class)->except(['show']);
    Route::resource('banner', BannerController::class)->except(['show']);
    Route::resource('contact-info', ContactController::class)->except(['show'])->parameters(['contact-info' => 'contactInfo']);
    Route::resource('testimonial', TestimonialController::class)->except(['show']);

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
});

require __DIR__.'/auth.php';
require __DIR__.'/rider.php';
require __DIR__.'/client.php';
