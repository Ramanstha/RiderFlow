<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Repositories\Contracts\RiderAttendanceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class RiderAttendanceService
{
    public function __construct(protected RiderAttendanceRepositoryInterface $attendances) {}

    public function list(array $filters = []): Collection
    {
        return $this->attendances->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->attendances->paginate($filters, $perPage);
    }

    public function find(int $id): RiderAttendance
    {
        return $this->attendances->findOrFail($id);
    }

    public function create(array $data): RiderAttendance
    {
        $this->guardDuplicateDate($data['rider_id'], $data['date']);

        return $this->attendances->create($data);
    }

    public function update(RiderAttendance $attendance, array $data): RiderAttendance
    {
        $riderId = $data['rider_id'] ?? $attendance->rider_id;
        $date = $data['date'] ?? $attendance->date->toDateString();

        $this->guardDuplicateDate($riderId, $date, $attendance->id);

        return $this->attendances->update($attendance, $data);
    }

    public function delete(RiderAttendance $attendance): void
    {
        $this->attendances->delete($attendance);
    }

    /**
     * Today's attendance row for a rider, if the rider has one.
     */
    public function todayFor(Rider $rider): ?RiderAttendance
    {
        return RiderAttendance::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', now()->toDateString())
            ->first();
    }

    /**
     * Self check-in from the rider portal.
     */
    public function checkIn(Rider $rider): RiderAttendance
    {
        if ($this->todayFor($rider)) {
            throw ValidationException::withMessages([
                'check_in' => 'You have already checked in today.',
            ]);
        }

        $now = now();

        return $this->attendances->create([
            'rider_id' => $rider->id,
            'date' => $now->toDateString(),
            'status' => AttendanceStatus::Present,
            'check_in' => $now->format('H:i:s'),
        ]);
    }

    /**
     * Self check-out from the rider portal.
     */
    public function checkOut(Rider $rider): RiderAttendance
    {
        $attendance = $this->todayFor($rider);

        if (! $attendance) {
            throw ValidationException::withMessages([
                'check_out' => 'You need to check in before you can check out.',
            ]);
        }

        if ($attendance->check_out) {
            throw ValidationException::withMessages([
                'check_out' => 'You have already checked out today.',
            ]);
        }

        return $this->attendances->update($attendance, [
            'check_out' => now()->format('H:i:s'),
        ]);
    }

    protected function guardDuplicateDate(int $riderId, string $date, ?int $ignoreId = null): void
    {
        $exists = RiderAttendance::query()
            ->where('rider_id', $riderId)
            ->whereDate('date', $date)
            ->when($ignoreId, fn ($query, $ignoreId) => $query->whereKeyNot($ignoreId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'date' => 'Attendance for this rider on this date has already been recorded.',
            ]);
        }
    }
}
