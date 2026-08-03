<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Performance\StorePerformanceRequest;
use App\Http\Requests\Performance\UpdatePerformanceRequest;
use App\Http\Resources\RiderPerformanceResource;
use App\Models\RiderPerformance;
use App\Services\RiderPerformanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderPerformanceController extends BaseApiController
{
    public function __construct(protected RiderPerformanceService $performances) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('performances.view');

        $performances = $this->performances->paginate($request->only(['rider_id', 'company_id', 'date_from', 'date_to']));

        return $this->success(RiderPerformanceResource::collection($performances));
    }

    public function store(StorePerformanceRequest $request): JsonResponse
    {
        $this->authorize('performances.manage');

        $performance = $this->performances->create($request->validated());

        return $this->success(new RiderPerformanceResource($performance->load('rider.company')), 'Performance record created successfully.', 201);
    }

    public function show(RiderPerformance $performance): JsonResponse
    {
        $this->authorize('performances.view');

        return $this->success(new RiderPerformanceResource($performance->load('rider.company')));
    }

    public function update(UpdatePerformanceRequest $request, RiderPerformance $performance): JsonResponse
    {
        $this->authorize('performances.manage');

        $performance = $this->performances->update($performance, $request->validated());

        return $this->success(new RiderPerformanceResource($performance->load('rider.company')), 'Performance record updated successfully.');
    }

    public function destroy(RiderPerformance $performance): JsonResponse
    {
        $this->authorize('performances.manage');

        $this->performances->delete($performance);

        return $this->success(message: 'Performance record removed successfully.');
    }
}
