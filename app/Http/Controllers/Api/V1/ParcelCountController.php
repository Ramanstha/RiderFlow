<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ParcelCount\StoreParcelCountRequest;
use App\Http\Requests\ParcelCount\UpdateParcelCountRequest;
use App\Http\Resources\DailyParcelCountResource;
use App\Models\DailyParcelCount;
use App\Services\ParcelCountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParcelCountController extends BaseApiController
{
    public function __construct(protected ParcelCountService $parcelCounts) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('parcels.view');

        $parcelCounts = $this->parcelCounts->paginate($request->only(['rider_id', 'company_id', 'date_from', 'date_to']));

        return $this->success(DailyParcelCountResource::collection($parcelCounts));
    }

    public function store(StoreParcelCountRequest $request): JsonResponse
    {
        $this->authorize('parcels.manage');

        $parcelCount = $this->parcelCounts->create($request->validated());

        return $this->success(new DailyParcelCountResource($parcelCount->load('rider.companies')), 'Parcel count recorded successfully.', 201);
    }

    public function show(DailyParcelCount $parcelCount): JsonResponse
    {
        $this->authorize('parcels.view');

        return $this->success(new DailyParcelCountResource($parcelCount->load('rider.companies')));
    }

    public function update(UpdateParcelCountRequest $request, DailyParcelCount $parcelCount): JsonResponse
    {
        $this->authorize('parcels.manage');

        $parcelCount = $this->parcelCounts->update($parcelCount, $request->validated());

        return $this->success(new DailyParcelCountResource($parcelCount->load('rider.companies')), 'Parcel count updated successfully.');
    }

    public function destroy(DailyParcelCount $parcelCount): JsonResponse
    {
        $this->authorize('parcels.manage');

        $this->parcelCounts->delete($parcelCount);

        return $this->success(message: 'Parcel count removed successfully.');
    }
}
