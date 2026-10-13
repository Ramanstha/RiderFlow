<?php

namespace App\Services;

use App\Models\Rider;
use App\Models\RiderPerformance;
use App\Repositories\Contracts\RiderAttendanceRepositoryInterface;
use App\Repositories\Contracts\RiderPerformanceRepositoryInterface;
use App\Support\AttendanceSummary;
use App\Support\NepaliDate;
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
        $data['period'] = $this->periodStart($data['period']);

        $this->guardDuplicatePeriod($data['rider_id'], $data['period']);

        return $this->performances->create($data);
    }

    public function update(RiderPerformance $performance, array $data): RiderPerformance
    {
        if (isset($data['period'])) {
            $data['period'] = $this->periodStart($data['period']);
        }

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
            'date_from' => NepaliDate::startOfMonth($period)->toDateString(),
            'date_to' => NepaliDate::endOfMonth($period)->toDateString(),
        ]);

        return AttendanceSummary::rate(
            AttendanceSummary::countPresent($records),
            $records->count()
        );
    }

    protected function guardDuplicatePeriod(int $riderId, string $period, ?int $ignoreId = null): void
    {
        $start = NepaliDate::startOfMonth(Carbon::parse($period));

        // The whole BS month, so a row stored on any day of it still counts.
        $exists = RiderPerformance::query()
            ->where('rider_id', $riderId)
            ->whereDate('period', '>=', $start->toDateString())
            ->whereDate('period', '<=', NepaliDate::endOfMonth($start)->toDateString())
            ->when($ignoreId, fn ($query, $ignoreId) => $query->whereKeyNot($ignoreId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'period' => 'A performance record for this rider and period already exists.',
            ]);
        }
    }

    /**
     * Performance is tracked per Nepali (BS) month: any date inside a month is
     * stored as the AD date of that month's 1st.
     */
    protected function periodStart(string $period): string
    {
        return NepaliDate::startOfMonth(Carbon::parse($period))->toDateString();
    }
}
