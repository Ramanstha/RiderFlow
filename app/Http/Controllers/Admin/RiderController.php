<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rider\StoreRiderRequest;
use App\Http\Requests\Rider\UpdateRiderRequest;
use App\Models\Rider;
use App\Services\RiderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderController extends Controller
{
    public function __construct(protected RiderService $riders) {}

    public function index(Request $request): View
    {
        $this->authorize('riders.view');

        $riders = $this->riders->paginate($request->only(['search', 'status', 'company_id']));

        return view('admin.rider.index', compact('riders'));
    }

    public function create(): View
    {
        $this->authorize('riders.manage');

        return view('admin.rider.create');
    }

    public function show(Rider $rider): RedirectResponse
    {
        $this->authorize('riders.view');

        return redirect()->route('rider.edit', $rider);
    }

    public function store(StoreRiderRequest $request): RedirectResponse
    {
        $this->authorize('riders.manage');

        $this->riders->create($request->validated(), $request->file('photo'));

        return redirect()->route('rider.index')->with('success', 'Rider added successfully.');
    }

    public function edit(Rider $rider): View
    {
        $this->authorize('riders.manage');

        return view('admin.rider.edit', compact('rider'));
    }

    public function update(UpdateRiderRequest $request, Rider $rider): RedirectResponse
    {
        $this->authorize('riders.manage');

        $this->riders->update($rider, $request->validated(), $request->file('photo'));

        return redirect()->route('rider.index')->with('success', 'Rider updated successfully.');
    }

    public function destroy(Rider $rider): RedirectResponse
    {
        $this->authorize('riders.manage');

        $this->riders->delete($rider);

        return redirect()->route('rider.index')->with('success', 'Rider removed successfully.');
    }
}
