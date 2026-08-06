<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Rider\StoreRiderRequest;
use App\Http\Requests\Rider\UpdateRiderRequest;
use App\Http\Resources\RiderResource;
use App\Models\Rider;
use App\Services\RiderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderController extends BaseApiController
{
    public function __construct(protected RiderService $riders) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('riders.view');

        $riders = $this->riders->paginate($request->only(['search', 'status', 'company_id']));

        return $this->success(RiderResource::collection($riders));
    }

    public function store(StoreRiderRequest $request): JsonResponse
    {
        $this->authorize('riders.manage');

        $rider = $this->riders->create($request->validated(), $request->file('photo'));

        return $this->success(new RiderResource($rider->load('companies')), 'Rider created successfully.', 201);
    }

    public function show(Rider $rider): JsonResponse
    {
        $this->authorize('riders.view');

        return $this->success(new RiderResource($rider->load('companies')));
    }

    public function update(UpdateRiderRequest $request, Rider $rider): JsonResponse
    {
        $this->authorize('riders.manage');

        $rider = $this->riders->update($rider, $request->validated(), $request->file('photo'));

        return $this->success(new RiderResource($rider->load('companies')), 'Rider updated successfully.');
    }

    public function destroy(Rider $rider): JsonResponse
    {
        $this->authorize('riders.manage');

        $this->riders->delete($rider);

        return $this->success(message: 'Rider removed successfully.');
    }
}
