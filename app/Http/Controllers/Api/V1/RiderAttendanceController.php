<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Http\Resources\RiderAttendanceResource;
use App\Models\RiderAttendance;
use App\Services\RiderAttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderAttendanceController extends BaseApiController
{
    public function __construct(protected RiderAttendanceService $attendances) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('attendances.view');

        $attendances = $this->attendances->paginate($request->only(['rider_id', 'company_id', 'status', 'date_from', 'date_to']));

        return $this->success(RiderAttendanceResource::collection($attendances));
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $this->authorize('attendances.manage');

        $attendance = $this->attendances->create($request->validated());

        return $this->success(new RiderAttendanceResource($attendance->load('rider.company')), 'Attendance recorded successfully.', 201);
    }

    public function show(RiderAttendance $attendance): JsonResponse
    {
        $this->authorize('attendances.view');

        return $this->success(new RiderAttendanceResource($attendance->load('rider.company')));
    }

    public function update(UpdateAttendanceRequest $request, RiderAttendance $attendance): JsonResponse
    {
        $this->authorize('attendances.manage');

        $attendance = $this->attendances->update($attendance, $request->validated());

        return $this->success(new RiderAttendanceResource($attendance->load('rider.company')), 'Attendance updated successfully.');
    }

    public function destroy(RiderAttendance $attendance): JsonResponse
    {
        $this->authorize('attendances.manage');

        $this->attendances->delete($attendance);

        return $this->success(message: 'Attendance record removed successfully.');
    }
}
