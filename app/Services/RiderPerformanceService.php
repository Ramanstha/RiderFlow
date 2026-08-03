<?php

namespace App\Services;

use App\Models\Rider;
use App\Models\RiderPerformance;
use App\Repositories\Contracts\RiderAttendanceRepositoryInterface;
use App\Repositories\Contracts\RiderPerformanceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class RiderPerformanceService
{
    public function __construct(
        protected RiderPerformanceRepositoryInterface $performances,
        protected RiderAttendanceRepositoryInterface $attendances,
    ) {}

    public function list(array $filters = []): Collection
    {
        return $this->performances->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->performances->paginate($filters, $perPage);
    }

    public function find(int $id): RiderPerformance
    {
        return $this->performances->findOrFail($id);
    }

    public function create(array $data): RiderPerformance
    {
        $this->guardDuplicatePeriod($data['rider_id'], $data['period']);

        return $this->performances->create($data);
    }

    public function update(RiderPerformance $performance, array $data): RiderPerformance
    {
        $riderId = $data['rider_id'] ?? $performance->rider_id;
        $period = $data['period'] ?? $performance->period->toDateString();

        $this->guardDuplicatePeriod($riderId, $period, $performance->id);

        return $this->performances->update($performance, $data);
    }

    public function delete(RiderPerformance $performance): void
    {
        $this->performances->delete($performance);
    }

    public function computeAttendanceRate(Rider $rider, Carbon $period): float
    {
        $records = $this->attendances->all([
            'rider_id' => $rider->id,
            'date_from' => $period->copy()->startOfMonth()->toDateString(),
            'date_to' => $period->copy()->endOfMonth()->toDateString(),
        ]);

        if ($records->isEmpty()) {
            return 0.0;
        }

        $present = $records->whereIn('status', ['Present', 'Late', 'Half Day'])->count();

        return round(($present / $records->count()) * 100, 2);
    }

    protected function guardDuplicatePeriod(int $riderId, string $period, ?int $ignoreId = null): void
    {
        $periodStart = Carbon::parse($period)->startOfMonth()->toDateString();

        $exists = RiderPerformance::query()
            ->where('rider_id', $riderId)
            ->whereDate('period', $periodStart)
            ->when($ignoreId, fn ($query, $ignoreId) => $query->whereKeyNot($ignoreId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'period' => 'A performance record for this rider and period already exists.',
            ]);
        }
    }
}
