<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AttendanceStatus;
use App\Enums\SalaryAdvanceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryAdvance\ReviewSalaryAdvanceRequest;
use App\Models\Rider;
use App\Models\SalaryAdvance;
use App\Support\NepaliDate;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaryAdvanceController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('riders.view');

        // Earnings accrue per Present day of the current BS month, as on the attendance calendar.
        $today = NepaliDate::fromAd(CarbonImmutable::today());
        $monthStart = NepaliDate::toAd($today['year'], $today['month']);
        $monthEnd = $monthStart->addDays(NepaliDate::daysInMonth($today['year'], $today['month']) - 1);
        $monthLabel = NepaliDate::MONTHS[$today['month']].' '.$today['year'];

        $advances = SalaryAdvance::query()
            ->with([
                'rider' => fn ($query) => $query->select('id', 'name', 'pay')
                    ->withCount(['attendances as present_days' => fn ($query) => $query
                        ->where('status', AttendanceStatus::Present)
                        ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])])
                    // An advance counts against the month it was approved in.
                    ->withSum(['salaryAdvances as month_advances' => fn ($query) => $query
                        ->where('status', SalaryAdvanceStatus::Approved)
                        ->whereBetween('reviewed_at', [$monthStart->startOfDay(), $monthEnd->endOfDay()])], 'amount'),
                'reviewer:id,name',
            ])
            ->when($request->filled('rider_id'), fn ($query) => $query->where('rider_id', $request->integer('rider_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            // Pending requests surface first so they get reviewed.
            ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [SalaryAdvanceStatus::Pending->value])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $riders = Rider::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.advance.index', compact('advances', 'riders', 'monthLabel'));
    }

    public function update(ReviewSalaryAdvanceRequest $request, SalaryAdvance $advance): RedirectResponse
    {
        if (! $advance->isPending()) {
            return back()->withErrors(['advance' => 'This request has already been reviewed.']);
        }

        $advance->update([
            ...$request->validated(),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('advance.index')->with('success', 'Advance request '.strtolower($advance->status->value).'.');
    }
}
