<?php

namespace App\Http\Controllers\Rider;

use App\Enums\SalaryAdvanceStatus;
use App\Http\Requests\Rider\Portal\StoreSalaryAdvanceRequest;
use App\Models\SalaryAdvance;
use App\Services\RiderAttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SalaryAdvanceController extends PortalController
{
    public function index(): View
    {
        return view('rider.advance.index', [
            'advances' => $this->rider()->salaryAdvances()->with('reviewer:id,name')->latest()->paginate(15),
        ]);
    }

    public function create(RiderAttendanceService $attendances): View
    {
        $calendar = $attendances->monthlyCalendar($this->rider());

        return view('rider.advance.create', [
            'rider' => $this->rider(),
            'earnings' => $calendar['payroll']['earnings'],
            'earningsMonth' => $calendar['bs']['label'],
            'payable' => $calendar['payroll']['payable'] === null ? null : max(0, $calendar['payroll']['payable']),
            'hasPending' => $this->rider()->salaryAdvances()->where('status', SalaryAdvanceStatus::Pending)->exists(),
        ]);
    }

    public function store(StoreSalaryAdvanceRequest $request): RedirectResponse
    {
        $this->rider()->salaryAdvances()->create($request->validated());

        return redirect()->route('rider.advance.index')->with('success', 'Advance salary request submitted successfully.');
    }

    /**
     * A rider may withdraw a request only while it is still awaiting review.
     */
    public function destroy(SalaryAdvance $advance): RedirectResponse
    {
        $this->authorizeOwnership($advance->rider_id);

        if (! $advance->isPending()) {
            return back()->withErrors(['advance' => 'Only pending requests can be cancelled.']);
        }

        $advance->delete();

        return redirect()->route('rider.advance.index')->with('success', 'Advance request cancelled.');
    }
}
