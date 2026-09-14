<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\AdminDashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected AdminDashboardService $dashboard) {}

    public function index(): View
    {
        $ridersCount = Rider::count();
        $clientsCount = Company::count();
        $attendancesCount = RiderAttendance::count();

        return view('admin.dashboard.index', [
            'ridersCount' => $ridersCount,
            'clientsCount' => $clientsCount,
            'attendancesCount' => $attendancesCount,
            'stats' => $this->dashboard->dashboard(),
        ]);
    }
}
