<?php

namespace App\Support;

use App\Enums\AttendanceStatus;
use App\Models\RiderAttendance;
use Illuminate\Support\Collection;

/**
 * Attendance totals for a set of records, shared by the admin reports and the
 * client and rider dashboards so every surface counts a day the same way.
 *
 * Status is cast to {@see AttendanceStatus}, so it must be matched against the
 * enum rather than its string value — Collection::whereIn() compares loosely
 * and never matches an enum against a plain string.
 */
class AttendanceSummary
{
    /**
     * @param  Collection<int, RiderAttendance>  $records
     * @return array{total_days: int, present_days: int, absent_days: int, attendance_rate: float}
     */
    public static function forRecords(Collection $records): array
    {
        return static::forStatuses($records->map(fn (RiderAttendance $attendance) => $attendance->status));
    }

    /**
     * The same totals from bare statuses, for callers that work out a day's
     * status without a row behind it — the calendar reads a day with no
     * record as Absent, and those days have to count towards the rate.
     *
     * @param  Collection<int, AttendanceStatus>  $statuses
     * @return array{total_days: int, present_days: int, absent_days: int, attendance_rate: float}
     */
    public static function forStatuses(Collection $statuses): array
    {
        $total = $statuses->count();
        $present = $statuses->filter(fn (AttendanceStatus $status) => $status === AttendanceStatus::Present)->count();

        return [
            'total_days' => $total,
            'present_days' => $present,
            'absent_days' => $statuses->filter(fn (AttendanceStatus $status) => $status === AttendanceStatus::Absent)->count(),
            'attendance_rate' => static::rate($present, $total),
        ];
    }

    /**
     * @param  Collection<int, RiderAttendance>  $records
     */
    public static function countPresent(Collection $records): int
    {
        return $records->where('status', AttendanceStatus::Present)->count();
    }

    /**
     * @param  Collection<int, RiderAttendance>  $records
     */
    public static function countAbsent(Collection $records): int
    {
        return $records->where('status', AttendanceStatus::Absent)->count();
    }

    public static function rate(int $present, int $total): float
    {
        return $total > 0 ? round(($present / $total) * 100, 2) : 0.0;
    }
}
