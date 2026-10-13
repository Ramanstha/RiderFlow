<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Enums\RiderStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\RiderAttendanceService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class RiderAttendanceCalendarTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    public function test_the_rider_list_only_shows_riders_that_have_been_marked(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $marked = Rider::factory()->create(['name' => 'Marked Rider']);
        Rider::factory()->create(['name' => 'Never Marked Rider']);

        RiderAttendance::factory()->create([
            'rider_id' => $marked->id,
            'date' => '2026-08-03',
            'status' => AttendanceStatus::Present,
        ]);

        $this->actingAs($admin)
            ->get(route('attendance.riders'))
            ->assertOk()
            ->assertSee('Marked Rider')
            ->assertDontSee('Never Marked Rider');
    }

    public function test_the_rider_list_can_be_searched(): void
    {
        $admin = $this->actingAsSuperAdmin();

        foreach (['Asha Gurung', 'Bikash Thapa'] as $name) {
            $rider = Rider::factory()->create(['name' => $name]);
            RiderAttendance::factory()->create(['rider_id' => $rider->id]);
        }

        $this->actingAs($admin)
            ->get(route('attendance.riders', ['search' => 'Asha']))
            ->assertOk()
            ->assertSee('Asha Gurung')
            ->assertDontSee('Bikash Thapa');
    }

    public function test_the_calendar_shows_the_requested_month_and_highlights_the_rider(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create(['name' => 'Calendar Rider']);

        RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-05-04',
            'status' => AttendanceStatus::Present,
        ]);

        $this->actingAs($admin)
            ->get(route('attendance.riders.show', [$rider, 'month' => '2083-01']))
            ->assertOk()
            ->assertSee('Calendar Rider')
            ->assertSee('बैशाख २०८३')
            ->assertSee('Apr – May 2026')
            ->assertSee('1 day tracked in Baisakh 2083');
    }

    public function test_the_calendar_only_loads_the_requested_month(): void
    {
        $rider = Rider::factory()->create();

        RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-05-04',
            'status' => AttendanceStatus::Present,
        ]);
        RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-05-05',
            'status' => AttendanceStatus::Absent,
        ]);
        RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            // 1 Jestha 2083 — the first day of the next Nepali month.
            'date' => '2026-05-15',
            'status' => AttendanceStatus::Present,
        ]);

        $calendar = app(RiderAttendanceService::class)->monthlyCalendar($rider, '2083-01');

        $this->assertSame(2, $calendar['summary']['total_days']);
        $this->assertSame(1, $calendar['summary']['present_days']);
        $this->assertSame(1, $calendar['summary']['absent_days']);
        $this->assertSame('2082-12', $calendar['previous']);
        $this->assertSame('2083-02', $calendar['next']);

        // Baisakh 2083 starts on Tuesday 14 April 2026 and has 31 days: 2 + 31 = 33 cells, padded to 5 weeks of 7.
        $this->assertSame('2026-04-14', $calendar['month']->toDateString());
        $this->assertCount(5, $calendar['weeks']);
        $this->assertSame(35, collect($calendar['weeks'])->flatten(1)->count());
        $this->assertSame(2, count(array_filter($calendar['weeks'][0], fn ($cell) => $cell === null)));
        $this->assertSame(1, $calendar['weeks'][0][2]['bs_day']);
    }

    public function test_paging_crosses_the_nepali_new_year(): void
    {
        $rider = Rider::factory()->create();
        $calendar = app(RiderAttendanceService::class)->monthlyCalendar($rider, '2083-12');

        $this->assertSame('2083-11', $calendar['previous']);
        $this->assertSame('2084-01', $calendar['next']);
        $this->assertSame('Chaitra 2083', $calendar['bs']['label']);
    }

    public function test_no_month_opens_the_current_nepali_month(): void
    {
        $this->travelTo('2026-10-06');

        $rider = Rider::factory()->create();
        $calendar = app(RiderAttendanceService::class)->monthlyCalendar($rider);

        // 6 October 2026 is 20 Ashwin 2083.
        $this->assertSame('2083-06', $calendar['current']);
        $this->assertSame('2026-09-17', $calendar['month']->toDateString());
    }

    public function test_an_unparseable_month_falls_back_to_the_current_month(): void
    {
        $this->travelTo('2026-10-06');

        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);

        foreach (['not-a-month', '2083-13', '1999-01'] as $month) {
            $this->actingAs($admin)
                ->get(route('attendance.riders.show', [$rider, 'month' => $month]))
                ->assertOk()
                ->assertSee('Ashwin 2083');
        }
    }

    public function test_a_viewer_can_read_the_calendar_but_a_guest_cannot(): void
    {
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);

        $this->get(route('attendance.riders'))->assertRedirect(route('login'));

        $viewer = $this->actingAsViewer();

        $this->actingAs($viewer)->get(route('attendance.riders'))->assertOk();
        $this->actingAs($viewer)->get(route('attendance.riders.show', $rider))->assertOk();
    }

    public function test_a_day_with_no_record_reads_absent(): void
    {
        $this->travelTo('2026-08-29');

        $rider = Rider::factory()->assignedFrom('2026-08-27')->create();

        RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-28',
            'status' => AttendanceStatus::Present,
        ]);

        $cells = $this->cellsByDate($rider, '2083-05');

        // The 27th and today both went unassigned, so both read Absent.
        $this->assertSame(AttendanceStatus::Absent, $cells['2026-08-27']['status']);
        $this->assertSame(AttendanceStatus::Present, $cells['2026-08-28']['status']);
        $this->assertSame(AttendanceStatus::Absent, $cells['2026-08-29']['status']);
    }

    public function test_days_before_the_rider_was_assigned_and_days_still_to_come_stay_blank(): void
    {
        $this->travelTo('2026-08-29');

        // On the fleet since the 1st, but only on a client roster from the 27th.
        $rider = Rider::factory()->assignedFrom('2026-08-27')->create(['created_at' => '2026-08-01 09:00:00']);
        $cells = $this->cellsByDate($rider, '2083-05');

        $this->assertNull($cells['2026-08-26']['status'], 'The day before the assignment started is not theirs to be absent on.');
        $this->assertNull($cells['2026-08-30']['status'], 'Tomorrow has not happened yet.');
    }

    public function test_a_rider_who_has_never_been_assigned_accrues_nothing(): void
    {
        $this->travelTo('2026-08-29');

        $rider = Rider::factory()->create(['created_at' => '2026-08-01 09:00:00']);
        $cells = $this->cellsByDate($rider, '2083-05');

        $this->assertNull($cells['2026-08-28']['status']);
        $this->assertSame(0, app(RiderAttendanceService::class)->monthlyCalendar($rider, '2083-05')['summary']['total_days']);
    }

    public function test_the_earliest_assignment_is_the_one_that_starts_the_clock(): void
    {
        $this->travelTo('2026-08-29');

        $rider = Rider::factory()
            ->assignedFrom('2026-08-28')
            ->assignedFrom('2026-08-20')
            ->create();

        $cells = $this->cellsByDate($rider, '2083-05');

        $this->assertNull($cells['2026-08-19']['status']);
        $this->assertSame(AttendanceStatus::Absent, $cells['2026-08-20']['status']);
    }

    public function test_an_inactive_rider_does_not_accrue_derived_absences(): void
    {
        $this->travelTo('2026-08-29');

        $rider = Rider::factory()->assignedFrom('2026-08-01')->create([
            'status' => RiderStatus::Inactive,
        ]);

        $cells = $this->cellsByDate($rider, '2083-05');

        $this->assertNull($cells['2026-08-28']['status']);
    }

    public function test_derived_absences_count_towards_the_summary(): void
    {
        $this->travelTo('2026-08-29');

        $rider = Rider::factory()->assignedFrom('2026-08-27')->create();

        RiderAttendance::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-28',
            'status' => AttendanceStatus::Present,
        ]);

        $summary = app(RiderAttendanceService::class)->monthlyCalendar($rider, '2083-05')['summary'];

        // The 27th, 28th and 29th — the rider's whole tenure so far.
        $this->assertSame(3, $summary['total_days']);
        $this->assertSame(1, $summary['present_days']);
        $this->assertSame(2, $summary['absent_days']);
        $this->assertSame(33.33, $summary['attendance_rate']);
    }

    /**
     * @return array<string, array{date: CarbonImmutable, attendance: ?RiderAttendance, status: ?AttendanceStatus}>
     */
    protected function cellsByDate(Rider $rider, string $month): array
    {
        $calendar = app(RiderAttendanceService::class)->monthlyCalendar($rider, $month);

        return collect($calendar['weeks'])
            ->flatten(1)
            ->filter()
            ->keyBy(fn (array $cell) => $cell['date']->toDateString())
            ->all();
    }
}
