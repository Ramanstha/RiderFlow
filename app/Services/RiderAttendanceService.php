<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Enums\RiderStatus;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Repositories\Contracts\RiderAttendanceRepositoryInterface;
use App\Support\AttendanceSummary;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
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
     * Riders that have at least one attendance record, with their running
     * totals — the roster behind the attendance-by-rider list. Riders who have
     * never been marked are left out entirely.
     *
     * @return Collection<int, Rider>
     */
    public function ridersWithAttendance(?string $search = null): Collection
    {
        return Rider::query()
            ->whereHas('attendances')
            ->when($search, fn (Builder $query, string $search) => $query->where(
                fn (Builder $query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
            ))
            ->withCount([
                'attendances',
                'attendances as present_count' => fn (Builder $query) => $query->where('status', AttendanceStatus::Present->value),
                'attendances as absent_count' => fn (Builder $query) => $query->where('status', AttendanceStatus::Absent->value),
            ])
            ->withMax('attendances as last_marked_on', 'date')
            ->with('companies')
            ->orderBy('name')
            ->get();
    }

    /**
     * One month of a rider's attendance, laid out as calendar weeks.
     *
     * Only the requested month is read, so paging back through history costs a
     * single indexed range query per month rather than loading a whole history.
     *
     * Every day the rider was on the roster for reads Present or Absent — a day
     * with no record means nothing was ever assigned to them, which is exactly
     * what being absent looks like, so there is no third "no record" state.
     * Only days that are not theirs to answer for — before their first
     * assignment started, or still to come — are left blank.
     *
     * @return array{
     *     month: CarbonImmutable,
     *     weeks: array<int, array<int, null|array{date: CarbonImmutable, attendance: ?RiderAttendance, status: ?AttendanceStatus}>>,
     *     summary: array{total_days: int, present_days: int, absent_days: int, attendance_rate: float},
     *     previous: string,
     *     next: string,
     *     current: string
     * }
     */
    public function monthlyCalendar(Rider $rider, ?string $month = null): array
    {
        $start = $this->resolveMonth($month);
        $end = $start->endOfMonth();

        $records = RiderAttendance::query()
            ->where('rider_id', $rider->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get();

        $byDate = $records->keyBy(fn (RiderAttendance $attendance) => $attendance->date->toDateString());

        // Read once for the whole month rather than per cell.
        $rosterStart = $this->rosterStartedOn($rider);

        // Pad with nulls so the first and last weeks line up under Sun–Sat.
        $cells = array_fill(0, $start->dayOfWeek, null);
        $statuses = collect();

        for ($day = $start; $day->lessThanOrEqualTo($end); $day = $day->addDay()) {
            $attendance = $byDate->get($day->toDateString());
            $status = $attendance?->status
                ?? ($this->isRosteredOn($rider, $day, $rosterStart) ? AttendanceStatus::Absent : null);

            if ($status) {
                $statuses->push($status);
            }

            $cells[] = [
                'date' => $day,
                'attendance' => $attendance,
                'status' => $status,
            ];
        }

        $cells = array_pad($cells, (int) ceil(count($cells) / 7) * 7, null);

        return [
            'month' => $start,
            'weeks' => array_chunk($cells, 7),
            'summary' => AttendanceSummary::forStatuses($statuses),
            'previous' => $start->subMonth()->format('Y-m'),
            'next' => $start->addMonth()->format('Y-m'),
            'current' => $start->format('Y-m'),
        ];
    }

    /**
     * Was the rider on the roster to work that day, and has the day happened?
     *
     * These are the same days the nightly sweep marks (see
     * {@see markDayFromParcels}), so the status the calendar fills in for a day
     * with no row is the one the sweep would have written for it.
     */
    protected function isRosteredOn(Rider $rider, CarbonImmutable $day, ?CarbonImmutable $rosterStart): bool
    {
        if ($day->greaterThan(CarbonImmutable::today())) {
            return false;
        }

        if ($rider->status !== RiderStatus::Active) {
            return false;
        }

        return $rosterStart !== null && ! $rosterStart->greaterThan($day);
    }

    /**
     * The day the rider's attendance starts counting: the start date of their
     * earliest assignment.
     *
     * A rider exists on the fleet from the moment they are created, but there
     * is no work to answer for until a client has them on their roster, so an
     * unassigned rider has no start day at all and accrues nothing.
     */
    protected function rosterStartedOn(Rider $rider): ?CarbonImmutable
    {
        $start = $rider->assignments()->min('start_date');

        return $start ? CarbonImmutable::parse($start)->startOfDay() : null;
    }

    /**
     * A `YYYY-MM` string as the first day of that month; anything unparseable
     * falls back to the current month rather than erroring out.
     */
    protected function resolveMonth(?string $month): CarbonImmutable
    {
        if (! $month) {
            return CarbonImmutable::now()->startOfMonth();
        }

        try {
            // Anchored to the 1st: 'Y-m' alone would borrow today's day number
            // and overflow short months (31 February lands in March).
            return CarbonImmutable::createFromFormat('Y-m-d', $month.'-01')->startOfDay();
        } catch (\Throwable) {
            return CarbonImmutable::now()->startOfMonth();
        }
    }

    /**
     * Keep a rider's attendance in step with their parcel load for one day:
     * parcels on the books means the rider worked, so they are Present; no
     * parcels left on that day means Absent.
     *
     * Called from the parcel side (see DailyParcelCountObserver), so every way
     * a parcel row is created, moved or removed — client assignment, admin CRUD
     * or the rider portal — lands the same attendance state.
     */
    public function syncFromParcels(int $riderId, string $date): RiderAttendance
    {
        return $this->applyParcelStatus($riderId, $date, $this->hasParcelsOn($riderId, $date));
    }

    /**
     * Close the books on one date for every active rider already assigned to a
     * client: Present for those who had parcels, Absent for everyone else.
     *
     * The observer only ever hears about days a parcel row exists for, so this
     * is what marks the days no parcel was assigned at all. Run nightly (see
     * routes/console.php) or over a range to backfill.
     *
     * @return array{present: int, absent: int}
     */
    public function markDayFromParcels(string $date): array
    {
        $day = CarbonImmutable::parse($date)->startOfDay();

        $withParcels = array_flip(
            DailyParcelCount::query()
                ->whereDate('date', $day->toDateString())
                ->where(fn (Builder $query) => $this->constrainToRealParcels($query))
                ->pluck('rider_id')
                ->all()
        );

        $counts = ['present' => 0, 'absent' => 0];

        Rider::query()
            ->where('status', RiderStatus::Active)
            // Attendance starts at the first assignment: nobody is absent for a
            // day no client had them on the roster for yet.
            ->whereHas('assignments', fn (Builder $query) => $query->whereDate('start_date', '<=', $day->toDateString()))
            ->orderBy('id')
            ->chunkById(200, function (Collection $riders) use ($day, $withParcels, &$counts) {
                foreach ($riders as $rider) {
                    $attendance = $this->applyParcelStatus(
                        $rider->id,
                        $day->toDateString(),
                        isset($withParcels[$rider->id]),
                    );

                    $counts[$attendance->status === AttendanceStatus::Present ? 'present' : 'absent']++;
                }
            });

        return $counts;
    }

    /**
     * Write the status the parcels imply.
     */
    protected function applyParcelStatus(int $riderId, string $date, bool $hasParcels): RiderAttendance
    {
        $attendance = RiderAttendance::query()
            ->where('rider_id', $riderId)
            ->whereDate('date', $date)
            ->first();

        $status = $hasParcels ? AttendanceStatus::Present : AttendanceStatus::Absent;

        if (! $attendance) {
            return $this->attendances->create([
                'rider_id' => $riderId,
                'date' => $date,
                'status' => $status,
            ]);
        }

        if ($attendance->status === $status) {
            return $attendance;
        }

        return $this->attendances->update($attendance, ['status' => $status]);
    }

    /**
     * Does the rider have any parcel activity on that date? Assigned parcels
     * count, and so do reported outcomes, so a row that was assigned zero but
     * worked anyway still reads as a working day.
     */
    protected function hasParcelsOn(int $riderId, string $date): bool
    {
        return DailyParcelCount::query()
            ->where('rider_id', $riderId)
            ->whereDate('date', $date)
            ->where(fn (Builder $query) => $this->constrainToRealParcels($query))
            ->exists();
    }

    /**
     * A parcel row only counts as work if there is something in it.
     */
    protected function constrainToRealParcels(Builder $query): Builder
    {
        return $query
            ->where('assigned', '>', 0)
            ->orWhere('delivered', '>', 0);
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
