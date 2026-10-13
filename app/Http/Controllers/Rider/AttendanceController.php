<?php

namespace App\Http\Controllers\Rider;

use App\Services\RiderAttendanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends PortalController
{
    public function __construct(protected RiderAttendanceService $attendances) {}

    public function index(Request $request): View
    {
        $rider = $this->rider();

        $calendar = $this->attendances->monthlyCalendar($rider, $request->query('month'));

        return view('rider.attendance.index', array_merge($calendar, ['rider' => $rider]));
    }
}
