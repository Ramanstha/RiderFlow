<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\AdminDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * The admin dashboard's charts row reads fleet-wide figures rather than the
 * per-company ones the client portal uses, so it gets its own aggregates.
 */
class AdminDashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected AdminDashboardService $dashboard;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dashboard = app(AdminDashboardService::class);
    }

    public function test_todays_attendance_counts_every_rider_on_the_fleet(): void
    {
        // Mid-week and mid-month, so neither period boundary falls on today.
        Carbon::setTestNow('2026-08-12 09:00:00');

        $this->attendance('2026-08-12', AttendanceStatus::Present);
        $this->attendance('2026-08-12', AttendanceStatus::Present);
        $this->attendance('2026-08-12', AttendanceStatus::Absent);
        $this->attendance('2026-08-11', AttendanceStatus::Absent);

        $this->assertSame([
            'present' => 2,
            'absent' => 1,
            'total' => 3,
            'rate' => 66.67,
        ], $this->dashboard->todayAttendance());
    }

    public function test_an_unmarked_day_reports_zeroes_rather_than_dividing_by_zero(): void
    {
        Carbon::setTestNow('2026-08-12 09:00:00');

        $this->assertSame([
            'present' => 0,
            'absent' => 0,
            'total' => 0,
            'rate' => 0.0,
        ], $this->dashboard->todayAttendance());
    }

    public function test_the_weekly_trend_runs_monday_to_sunday_with_gaps_as_zero(): void
    {
        Carbon::setTestNow('2026-08-12 09:00:00'); // Wednesday

        $this->parcels('2026-08-10', 30, 28); // Mon
        $this->parcels('2026-08-10', 20, 15); // Mon, second rider
        $this->parcels('2026-08-12', 40, 39); // Wed
        $this->parcels('2026-08-09', 99, 99); // Sunday of the week before

        $weekly = $this->dashboard->deliveryTrends()['weekly'];

        $this->assertSame(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], $weekly['labels']);
        $this->assertSame([50, 0, 40, 0, 0, 0, 0], $weekly['assigned']);
        $this->assertSame([43, 0, 39, 0, 0, 0, 0], $weekly['delivered']);
    }

    public function test_the_monthly_trend_buckets_the_month_into_seven_day_blocks(): void
    {
        Carbon::setTestNow('2026-08-12 09:00:00');

        $this->parcels('2026-08-01', 10, 9);   // Week 1
        $this->parcels('2026-08-07', 5, 5);    // Week 1
        $this->parcels('2026-08-08', 12, 11);  // Week 2
        $this->parcels('2026-08-31', 7, 6);    // Week 5
        $this->parcels('2026-07-31', 99, 99);  // previous month

        $monthly = $this->dashboard->deliveryTrends()['monthly'];

        $this->assertSame(['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'], $monthly['labels']);
        $this->assertSame([15, 12, 0, 0, 7], $monthly['assigned']);
        $this->assertSame([14, 11, 0, 0, 6], $monthly['delivered']);
    }

    protected function attendance(string $date, AttendanceStatus $status): RiderAttendance
    {
        return RiderAttendance::factory()->create([
            'rider_id' => Rider::factory(),
            'date' => $date,
            'status' => $status,
        ]);
    }

    protected function parcels(string $date, int $assigned, int $delivered): DailyParcelCount
    {
        return DailyParcelCount::factory()->create([
            'rider_id' => Rider::factory(),
            'date' => $date,
            'assigned' => $assigned,
            'delivered' => $delivered,
        ]);
    }
}
