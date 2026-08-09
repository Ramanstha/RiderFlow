<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\RiderAttendanceService;
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
            ->get(route('attendance.riders.show', [$rider, 'month' => '2026-05']))
            ->assertOk()
            ->assertSee('Calendar Rider')
            ->assertSee('May 2026')
            ->assertSee('1 day recorded in May 2026');
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
            'date' => '2026-06-01',
            'status' => AttendanceStatus::Present,
        ]);

        $calendar = app(RiderAttendanceService::class)->monthlyCalendar($rider, '2026-05');

        $this->assertSame(2, $calendar['summary']['total_days']);
        $this->assertSame(1, $calendar['summary']['present_days']);
        $this->assertSame(1, $calendar['summary']['absent_days']);
        $this->assertSame('2026-04', $calendar['previous']);
        $this->assertSame('2026-06', $calendar['next']);

        // May 2026 starts on a Friday and has 31 days: 5 + 31 = 36 cells, padded to 6 weeks of 7.
        $this->assertCount(6, $calendar['weeks']);
        $this->assertSame(42, collect($calendar['weeks'])->flatten(1)->count());
        $this->assertSame(5, count(array_filter($calendar['weeks'][0], fn ($cell) => $cell === null)));
    }

    public function test_a_short_month_is_not_overflowed_by_todays_day_number(): void
    {
        $this->travelTo('2026-08-31');

        $rider = Rider::factory()->create();
        $calendar = app(RiderAttendanceService::class)->monthlyCalendar($rider, '2026-02');

        $this->assertSame('2026-02-01', $calendar['month']->toDateString());
        $this->assertSame('2026-02', $calendar['current']);
    }

    public function test_an_unparseable_month_falls_back_to_the_current_month(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);

        $this->actingAs($admin)
            ->get(route('attendance.riders.show', [$rider, 'month' => 'not-a-month']))
            ->assertOk()
            ->assertSee(now()->format('F Y'));
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
}
