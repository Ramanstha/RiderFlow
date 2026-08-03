<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Performance\StorePerformanceRequest;
use App\Http\Requests\Performance\UpdatePerformanceRequest;
use App\Models\Rider;
use App\Models\RiderPerformance;
use App\Services\RiderPerformanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderPerformanceController extends Controller
{
    public function __construct(protected RiderPerformanceService $performances) {}

    public function index(Request $request): View
    {
        $this->authorize('performances.view');

        $performances = $this->performances->paginate($request->only(['rider_id', 'date_from', 'date_to']));
        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.performance.index', compact('performances', 'riders'));
    }

    public function create(): View
    {
        $this->authorize('performances.manage');

        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.performance.create', compact('riders'));
    }

    public function store(StorePerformanceRequest $request): RedirectResponse
    {
        $this->authorize('performances.manage');

        $this->performances->create($request->validated());

        return redirect()->route('performance.index')->with('success', 'Performance record created successfully.');
    }

    public function edit(RiderPerformance $performance): View
    {
        $this->authorize('performances.manage');

        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.performance.edit', compact('performance', 'riders'));
    }

    public function update(UpdatePerformanceRequest $request, RiderPerformance $performance): RedirectResponse
    {
        $this->authorize('performances.manage');

        $this->performances->update($performance, $request->validated());

        return redirect()->route('performance.index')->with('success', 'Performance record updated successfully.');
    }

    public function destroy(RiderPerformance $performance): RedirectResponse
    {
        $this->authorize('performances.manage');

        $this->performances->delete($performance);

        return redirect()->route('performance.index')->with('success', 'Performance record removed successfully.');
    }
}
