<?php

namespace App\Services;

use App\Enums\AssignmentStatus;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAssignment;
use App\Models\RiderAttendance;
use App\Models\RiderPerformance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * Read-side aggregates for the rider portal dashboard. Everything here is
 * scoped to a single rider — the portal never exposes another rider's data.
 */
class RiderPortalService
{
    public function dashboard(Rider $rider): array
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        return [
            'today_attendance' => $this->todayAttendance($rider),
            'today_parcels' => $this->todayParcels($rider),
            'attendance' => $this->attendanceSummary($rider, $monthStart, $monthEnd),
            'parcels' => $this->parcelSummary($rider, $monthStart, $monthEnd),
            'active_assignment' => $this->activeAssignment($rider),
            'latest_performance' => $this->latestPerformance($rider),
            'recent_parcels' => $this->recentParcels($rider),
        ];
    }

    public function todayAttendance(Rider $rider): ?RiderAttendance
    {
        return RiderAttendance::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', now()->toDateString())
            ->first();
    }

    public function todayParcels(Rider $rider): ?DailyParcelCount
    {
        return DailyParcelCount::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', now()->toDateString())
            ->first();
    }

    /**
     * @return array{total_days: int, present_days: int, absent_days: int, leave_days: int, attendance_rate: float}
     */
    public function attendanceSummary(Rider $rider, string $from, string $to): array
    {
        $records = RiderAttendance::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->get();

        $total = $records->count();
        $present = $records->whereIn('status', ['Present', 'Late', 'Half Day'])->count();

        return [
            'total_days' => $total,
            'present_days' => $present,
            'absent_days' => $records->where('status', 'Absent')->count(),
            'leave_days' => $records->where('status', 'Leave')->count(),
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0.0,
        ];
    }

    /**
     * @return array{assigned: int, delivered: int, failed: int, returned: int, success_rate: float}
     */
    public function parcelSummary(Rider $rider, string $from, string $to): array
    {
        $records = DailyParcelCount::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->get();

        $assigned = (int) $records->sum('assigned');
        $delivered = (int) $records->sum('delivered');

        return [
            'assigned' => $assigned,
            'delivered' => $delivered,
            'failed' => (int) $records->sum('failed'),
            'returned' => (int) $records->sum('returned'),
            'success_rate' => $assigned > 0 ? round(($delivered / $assigned) * 100, 2) : 0.0,
        ];
    }

    public function activeAssignment(Rider $rider): ?RiderAssignment
    {
        return RiderAssignment::query()
            ->with('company')
            ->where('rider_id', $rider->id)
            ->where('status', AssignmentStatus::Assigned)
            ->orderByDesc('start_date')
            ->first();
    }

    public function latestPerformance(Rider $rider): ?RiderPerformance
    {
        return RiderPerformance::query()
            ->where('rider_id', $rider->id)
            ->orderByDesc('period')
            ->first();
    }

    /**
     * Last seven days of parcel counts, oldest first, with gaps filled in
     * so the dashboard chart always plots a full week.
     *
     * @return \Illuminate\Support\Collection<int, array{date: string, label: string, delivered: int, failed: int, assigned: int}>
     */
    public function recentParcels(Rider $rider, int $days = 7): \Illuminate\Support\Collection
    {
        $start = now()->subDays($days - 1)->startOfDay();

        /** @var Collection<int, DailyParcelCount> $records */
        $records = DailyParcelCount::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', '>=', $start->toDateString())
            ->get()
            ->keyBy(fn (DailyParcelCount $count) => $count->date->toDateString());

        return collect(range(0, $days - 1))->map(function (int $offset) use ($start, $records) {
            $day = (clone $start)->addDays($offset);
            $record = $records->get($day->toDateString());

            return [
                'date' => $day->toDateString(),
                'label' => $day->format('D'),
                'assigned' => (int) ($record->assigned ?? 0),
                'delivered' => (int) ($record->delivered ?? 0),
                'failed' => (int) ($record->failed ?? 0),
            ];
        });
    }

    public function currentPeriod(): Carbon
    {
        return now()->startOfMonth();
    }
}
