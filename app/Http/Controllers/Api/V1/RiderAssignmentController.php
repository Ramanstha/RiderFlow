<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Assignment\StoreAssignmentRequest;
use App\Http\Requests\Assignment\UpdateAssignmentRequest;
use App\Http\Resources\RiderAssignmentResource;
use App\Models\RiderAssignment;
use App\Services\RiderAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderAssignmentController extends BaseApiController
{
    public function __construct(protected RiderAssignmentService $assignments) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('assignments.view');

        $assignments = $this->assignments->paginate($request->only(['rider_id', 'company_id', 'type']));

        return $this->success(RiderAssignmentResource::collection($assignments));
    }

    public function store(StoreAssignmentRequest $request): JsonResponse
    {
        $this->authorize('assignments.manage');

        $assignment = $this->assignments->create($request->validated());

        return $this->success(new RiderAssignmentResource($assignment->load(['rider', 'company'])), 'Assignment created successfully.', 201);
    }

    public function show(RiderAssignment $assignment): JsonResponse
    {
        $this->authorize('assignments.view');

        return $this->success(new RiderAssignmentResource($assignment->load(['rider', 'company'])));
    }

    public function update(UpdateAssignmentRequest $request, RiderAssignment $assignment): JsonResponse
    {
        $this->authorize('assignments.manage');

        $assignment = $this->assignments->update($assignment, $request->validated());

        return $this->success(new RiderAssignmentResource($assignment->load(['rider', 'company'])), 'Assignment updated successfully.');
    }

    public function destroy(RiderAssignment $assignment): JsonResponse
    {
        $this->authorize('assignments.manage');

        $this->assignments->delete($assignment);

        return $this->success(message: 'Assignment removed successfully.');
    }
}
