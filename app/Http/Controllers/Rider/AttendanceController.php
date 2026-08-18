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

        $filters = $request->only(['status', 'date_from', 'date_to']);
        $filters['rider_id'] = $rider->id;

        return view('rider.attendance.index', [
            'attendances' => $this->attendances->paginate($filters),
        ]);
    }
}
