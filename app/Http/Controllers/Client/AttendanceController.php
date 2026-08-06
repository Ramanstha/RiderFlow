<?php

namespace App\Http\Controllers\Client;

use App\Models\RiderAttendance;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends PortalController
{
    public function index(Request $request): View
    {
        $company = $this->company();

        $records = RiderAttendance::query()
            ->with('rider:id,name')
            ->whereIn('rider_id', $company->riders()->select('riders.id'))
            ->when($request->integer('rider_id'), fn ($q, $id) => $q->where('rider_id', $id))
            ->when($request->string('status')->trim()->value(), fn ($q, $status) => $q->where('status', $status))
            ->when($request->date('date_from'), fn ($q, $date) => $q->whereDate('date', '>=', $date))
            ->when($request->date('date_to'), fn ($q, $date) => $q->whereDate('date', '<=', $date))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        return view('client.attendance.index', [
            'records' => $records,
            'riders' => $company->riders()->orderBy('name')->get(['riders.id', 'riders.name']),
        ]);
    }
}
