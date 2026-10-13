<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Models\RiderPerformance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class NepaliDateDisplayTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    public function test_listed_dates_are_shown_in_bs(): void
    {
        $admin = $this->actingAsSuperAdmin();

        RiderAttendance::factory()->create([
            'rider_id' => Rider::factory(),
            'date' => '2026-10-06',
            'status' => AttendanceStatus::Present,
        ]);

        $this->actingAs($admin)
            ->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('2083-06-20')
            ->assertDontSee('2026-10-06');
    }

    public function test_date_filters_show_bs_but_submit_ad(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)
            ->get(route('attendance.index', ['date_from' => '2026-10-06', 'date_to' => 'garbage']))
            ->assertOk()
            // The person sees the BS date; the hidden field keeps the AD value the query uses.
            ->assertSee('value="2083-06-20"', false)
            ->assertSee('name="date_from" value="2026-10-06"', false)
            // A malformed value is dropped rather than breaking the page.
            ->assertSee('name="date_to" value=""', false);
    }

    public function test_a_performance_period_is_stored_as_the_start_of_its_bs_month(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create();

        $this->actingAs($admin)->post(route('performance.store'), [
            'rider_id' => $rider->id,
            // 20 Ashwin 2083 — any day inside the month.
            'period' => '2026-10-06',
            'score' => 80,
        ])->assertRedirect();

        // 1 Ashwin 2083.
        $this->assertSame('2026-09-17', RiderPerformance::sole()->period->toDateString());

        // A second record for another day of the same BS month is a duplicate.
        $this->actingAs($admin)->post(route('performance.store'), [
            'rider_id' => $rider->id,
            'period' => '2026-10-17',
            'score' => 70,
        ])->assertSessionHasErrors('period');

        $this->actingAs($admin)
            ->get(route('performance.index'))
            ->assertSee('Ashwin 2083');
    }
}
