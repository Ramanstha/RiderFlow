<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\StoreAssignmentRequest;
use App\Http\Requests\Assignment\UpdateAssignmentRequest;
use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAssignment;
use App\Services\RiderAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderAssignmentController extends Controller
{
    public function __construct(protected RiderAssignmentService $assignments) {}

    public function index(Request $request): View
    {
        $this->authorize('assignments.view');

        $assignments = $this->assignments->paginate($request->only(['rider_id', 'company_id', 'type']));
        $riders = Rider::query()->orderBy('name')->get();
        $companies = Company::query()->orderBy('name')->get();

        return view('admin.assignment.index', compact('assignments', 'riders', 'companies'));
    }

    public function create(): View
    {
        $this->authorize('assignments.manage');

        $riders = Rider::query()->orderBy('name')->get();
        $companies = Company::query()->orderBy('name')->get();

        return view('admin.assignment.create', compact('riders', 'companies'));
    }

    public function store(StoreAssignmentRequest $request): RedirectResponse
    {
        $this->authorize('assignments.manage');

        $this->assignments->create($request->validated());

        return redirect()->route('assignment.index')->with('success', 'Assignment created successfully.');
    }

    public function edit(RiderAssignment $assignment): View
    {
        $this->authorize('assignments.manage');

        $riders = Rider::query()->orderBy('name')->get();
        $companies = Company::query()->orderBy('name')->get();

        return view('admin.assignment.edit', compact('assignment', 'riders', 'companies'));
    }

    public function update(UpdateAssignmentRequest $request, RiderAssignment $assignment): RedirectResponse
    {
        $this->authorize('assignments.manage');

        $this->assignments->update($assignment, $request->validated());

        return redirect()->route('assignment.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(RiderAssignment $assignment): RedirectResponse
    {
        $this->authorize('assignments.manage');

        $this->assignments->delete($assignment);

        return redirect()->route('assignment.index')->with('success', 'Assignment removed successfully.');
    }
}
