<?php

namespace App\Http\Controllers\Rider;

use App\Http\Requests\Rider\Portal\StoreParcelCountRequest;
use App\Http\Requests\Rider\Portal\UpdateParcelCountRequest;
use App\Models\DailyParcelCount;
use App\Services\ParcelCountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParcelCountController extends PortalController
{
    public function __construct(protected ParcelCountService $parcelCounts) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to']);
        $filters['rider_id'] = $this->rider()->id;

        return view('rider.parcel.index', [
            'parcelCounts' => $this->parcelCounts->paginate($filters),
        ]);
    }

    public function create(): View
    {
        return view('rider.parcel.create');
    }

    public function store(StoreParcelCountRequest $request): RedirectResponse
    {
        $this->parcelCounts->create([
            ...$request->validated(),
            'rider_id' => $this->rider()->id,
        ]);

        return redirect()->route('rider.parcel.index')->with('success', 'Parcel count submitted successfully.');
    }

    public function edit(DailyParcelCount $parcel): View
    {
        $this->authorizeOwnership($parcel->rider_id);

        return view('rider.parcel.edit', ['parcelCount' => $parcel->load('assignedByCompany:id,name')]);
    }

    public function update(UpdateParcelCountRequest $request, DailyParcelCount $parcel): RedirectResponse
    {
        $this->authorizeOwnership($parcel->rider_id);

        $this->parcelCounts->update($parcel, [
            ...$request->validated(),
            'rider_id' => $this->rider()->id,
        ]);

        return redirect()->route('rider.parcel.index')->with('success', 'Parcel count updated successfully.');
    }
}
