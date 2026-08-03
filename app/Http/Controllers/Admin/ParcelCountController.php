<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParcelCount\StoreParcelCountRequest;
use App\Http\Requests\ParcelCount\UpdateParcelCountRequest;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Services\ParcelCountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParcelCountController extends Controller
{
    public function __construct(protected ParcelCountService $parcelCounts) {}

    public function index(Request $request): View
    {
        $this->authorize('parcels.view');

        $parcelCounts = $this->parcelCounts->paginate($request->only(['rider_id', 'date_from', 'date_to']));
        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.parcel.index', compact('parcelCounts', 'riders'));
    }

    public function create(): View
    {
        $this->authorize('parcels.manage');

        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.parcel.create', compact('riders'));
    }

    public function store(StoreParcelCountRequest $request): RedirectResponse
    {
        $this->authorize('parcels.manage');

        $this->parcelCounts->create($request->validated());

        return redirect()->route('parcel.index')->with('success', 'Parcel count recorded successfully.');
    }

    public function edit(DailyParcelCount $parcel): View
    {
        $this->authorize('parcels.manage');

        $riders = Rider::query()->orderBy('name')->get();

        return view('admin.parcel.edit', ['parcelCount' => $parcel, 'riders' => $riders]);
    }

    public function update(UpdateParcelCountRequest $request, DailyParcelCount $parcel): RedirectResponse
    {
        $this->authorize('parcels.manage');

        $this->parcelCounts->update($parcel, $request->validated());

        return redirect()->route('parcel.index')->with('success', 'Parcel count updated successfully.');
    }

    public function destroy(DailyParcelCount $parcel): RedirectResponse
    {
        $this->authorize('parcels.manage');

        $this->parcelCounts->delete($parcel);

        return redirect()->route('parcel.index')->with('success', 'Parcel count removed successfully.');
    }
}
