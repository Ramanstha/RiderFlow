<?php

namespace Tests\Feature;

use App\Enums\AssignmentType;
use App\Enums\AttendanceStatus;
use App\Enums\RiderStatus;
use App\Models\Company;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Services\ClientParcelAssignmentService;
use App\Services\ParcelCountService;
use App\Services\RiderAttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParcelDrivenAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigning_parcels_marks_the_rider_present_for_that_day(): void
    {
        $rider = Rider::factory()->create();

        DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-03',
            'assigned' => 12,
            'delivered' => 0,
        ]);

        $this->assertMarked($rider, '2026-08-03', AttendanceStatus::Present);
    }

    public function test_a_parcel_row_with_no_parcels_leaves_the_rider_absent(): void
    {
        $rider = Rider::factory()->create();

        $parcels = DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-03',
            'assigned' => 5,
            'delivered' => 0,
        ]);

        app(ParcelCountService::class)->update($parcels, ['assigned' => 0]);

        $this->assertMarked($rider, '2026-08-03', AttendanceStatus::Absent);
    }

    public function test_removing_the_parcels_flips_the_day_back_to_absent(): void
    {
        $rider = Rider::factory()->create();

        $parcels = DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-03',
            'assigned' => 9,
        ]);

        app(ParcelCountService::class)->delete($parcels);

        $this->assertMarked($rider, '2026-08-03', AttendanceStatus::Absent);
    }

    public function test_moving_parcels_to_another_day_moves_the_present_mark_with_them(): void
    {
        $rider = Rider::factory()->create();

        $parcels = DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-03',
            'assigned' => 4,
            'delivered' => 0,
        ]);

        app(ParcelCountService::class)->update($parcels, ['date' => '2026-08-04']);

        $this->assertMarked($rider, '2026-08-03', AttendanceStatus::Absent);
        $this->assertMarked($rider, '2026-08-04', AttendanceStatus::Present);
    }

    public function test_a_client_assignment_marks_the_rider_present(): void
    {
        $company = Company::factory()->create();
        $rider = Rider::factory()->create();
        $rider->assignments()->create([
            'company_id' => $company->id,
            'type' => AssignmentType::LastMile,
            'start_date' => '2026-08-01',
        ]);

        app(ClientParcelAssignmentService::class)->assign($company, [
            'rider_id' => $rider->id,
            'date' => '2026-08-05',
            'assigned' => 20,
        ]);

        $this->assertMarked($rider, '2026-08-05', AttendanceStatus::Present);
    }

    public function test_an_empty_parcel_row_marks_the_rider_absent(): void
    {
        $rider = Rider::factory()->create();

        DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-03',
            'assigned' => 0,
            'delivered' => 0,
        ]);

        $this->assertMarked($rider, '2026-08-03', AttendanceStatus::Absent);
    }

    public function test_marking_a_day_marks_riders_with_no_parcel_row_at_all_absent(): void
    {
        $this->travelTo('2026-08-06');

        $worked = Rider::factory()->create();
        $idle = Rider::factory()->create();

        DailyParcelCount::factory()->create([
            'rider_id' => $worked->id,
            'date' => '2026-08-06',
            'assigned' => 15,
        ]);

        $counts = app(RiderAttendanceService::class)->markDayFromParcels('2026-08-06');

        $this->assertMarked($worked, '2026-08-06', AttendanceStatus::Present);
        $this->assertMarked($idle, '2026-08-06', AttendanceStatus::Absent);
        $this->assertSame(['present' => 1, 'absent' => 1], $counts);
    }

    public function test_inactive_riders_and_days_before_a_rider_joined_are_skipped(): void
    {
        $this->travelTo('2026-08-06');

        $inactive = Rider::factory()->create(['status' => RiderStatus::Inactive]);
        $newJoiner = Rider::factory()->create(['created_at' => '2026-08-05 09:00:00']);

        app(RiderAttendanceService::class)->markDayFromParcels('2026-08-04');

        $this->assertDatabaseCount('rider_attendances', 0);

        app(RiderAttendanceService::class)->markDayFromParcels('2026-08-05');

        $this->assertMarked($newJoiner, '2026-08-05', AttendanceStatus::Absent);
        $this->assertNull($inactive->attendances()->first());
    }

    public function test_the_command_backfills_a_range_and_refuses_future_dates(): void
    {
        $this->travelTo('2026-08-06');

        $rider = Rider::factory()->create(['created_at' => '2026-08-01 08:00:00']);

        DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => '2026-08-05',
            'assigned' => 8,
        ]);

        $this->artisan('attendance:mark-from-parcels', ['--from' => '2026-08-04', '--to' => '2026-08-06'])
            ->assertSuccessful();

        $this->assertMarked($rider, '2026-08-04', AttendanceStatus::Absent);
        $this->assertMarked($rider, '2026-08-05', AttendanceStatus::Present);
        $this->assertMarked($rider, '2026-08-06', AttendanceStatus::Absent);

        $this->artisan('attendance:mark-from-parcels', ['--date' => '2026-08-07'])
            ->assertFailed();
    }

    /**
     * Asserted through the model rather than the raw row: the date column is
     * stored with a time component, which assertDatabaseHas would not match.
     */
    protected function assertMarked(Rider $rider, string $date, AttendanceStatus $status): void
    {
        $attendance = RiderAttendance::query()
            ->where('rider_id', $rider->id)
            ->whereDate('date', $date)
            ->first();

        $this->assertNotNull($attendance, "No attendance was recorded for {$date}.");
        $this->assertSame($status, $attendance->status, "Expected {$status->value} on {$date}.");
    }
}
