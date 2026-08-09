<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\RiderAttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderAttendanceController extends Controller
{
    public function __construct(protected RiderAttendanceService $attendances) {}

    public function index(Request $request): View
    {
        $this->authorize('attendances.view');

        $attendances = $this->attendances->paginate($request->only(['rider_id', 'status', 'date_from', 'date_to']));
        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.attendance.index', compact('attendances', 'riders'));
    }

    /**
     * Riders that have been marked at least once — the entry point into the
     * per-rider attendance calendars.
     */
    public function riders(Request $request): View
    {
        $this->authorize('attendances.view');

        $search = trim((string) $request->query('search')) ?: null;
        $riders = $this->attendances->ridersWithAttendance($search);

        return view('admin.attendance.riders', compact('riders', 'search'));
    }

    /**
     * One rider's attendance for a single month, as a calendar.
     */
    public function calendar(Request $request, Rider $rider): View
    {
        $this->authorize('attendances.view');

        $calendar = $this->attendances->monthlyCalendar($rider, $request->query('month'));

        return view('admin.attendance.calendar', array_merge($calendar, ['rider' => $rider]));
    }

    public function create(): View
    {
        $this->authorize('attendances.manage');

        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.attendance.create', compact('riders'));
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $this->authorize('attendances.manage');

        $this->attendances->create($request->validated());

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function edit(RiderAttendance $attendance): View
    {
        $this->authorize('attendances.manage');

        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.attendance.edit', compact('attendance', 'riders'));
    }

    public function update(UpdateAttendanceRequest $request, RiderAttendance $attendance): RedirectResponse
    {
        $this->authorize('attendances.manage');

        $this->attendances->update($attendance, $request->validated());

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy(RiderAttendance $attendance): RedirectResponse
    {
        $this->authorize('attendances.manage');

        $this->attendances->delete($attendance);

        return redirect()->route('attendance.index')->with('success', 'Attendance record removed successfully.');
    }
}
