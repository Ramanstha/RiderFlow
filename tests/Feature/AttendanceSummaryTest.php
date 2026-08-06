<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\RiderPortalService;
use App\Support\AttendanceSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Attendance status is cast to an enum, and Collection::whereIn() compares
 * loosely — so matching it against a plain string silently counts nothing and
 * every rate reads 0%. These cover the counting itself, not the surfaces.
 */
class AttendanceSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_counts_enum_cast_statuses(): void
    {
        $rider = Rider::factory()->create();

        $records = collect([
            $this->attendance($rider, '2026-08-01', AttendanceStatus::Present),
            $this->attendance($rider, '2026-08-02', AttendanceStatus::Present),
            $this->attendance($rider, '2026-08-03', AttendanceStatus::Absent),
        ]);

        $this->assertSame([
            'total_days' => 3,
            'present_days' => 2,
            'absent_days' => 1,
            'attendance_rate' => 66.67,
        ], AttendanceSummary::forRecords($records));
    }

    public function test_an_empty_set_reports_a_zero_rate_rather_than_dividing_by_zero(): void
    {
        $this->assertSame([
            'total_days' => 0,
            'present_days' => 0,
            'absent_days' => 0,
            'attendance_rate' => 0.0,
        ], AttendanceSummary::forRecords(collect()));
    }

    public function test_the_rider_dashboard_reports_a_real_attendance_rate(): void
    {
        $rider = Rider::factory()->create();

        $this->attendance($rider, now()->startOfMonth()->toDateString(), AttendanceStatus::Present);
        $this->attendance($rider, now()->startOfMonth()->addDay()->toDateString(), AttendanceStatus::Absent);

        $summary = app(RiderPortalService::class)->attendanceSummary(
            $rider,
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        );

        $this->assertSame(2, $summary['total_days']);
        $this->assertSame(1, $summary['present_days']);
        $this->assertSame(50.0, $summary['attendance_rate']);
    }

    protected function attendance(Rider $rider, string $date, AttendanceStatus $status): RiderAttendance
    {
        return RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            'date' => $date,
            'status' => $status,
        ]);
    }
}
