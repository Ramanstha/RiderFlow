<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAttendance;

class DashboardController extends Controller
{
    public function index()
    {
        $ridersCount = Rider::count();
        $clientsCount = Company::count();
        $attendancesCount = RiderAttendance::count();

        return view('admin.dashboard.index', compact('ridersCount', 'clientsCount', 'attendancesCount'));
    }
}
