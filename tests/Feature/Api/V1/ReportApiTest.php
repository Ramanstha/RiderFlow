<?php

namespace Tests\Feature\Api\V1;

use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_returns_attendance_summary(): void
    {
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id, 'status' => 'Present', 'date' => '2026-07-01']);
        RiderAttendance::factory()->create(['rider_id' => $rider->id, 'status' => 'Absent', 'date' => '2026-07-02']);

        $response = $this->getJson('/api/v1/reports/attendance');

        $response->assertOk()->assertJsonPath('data.0.rider_id', $rider->id);
    }

    public function test_it_returns_parcel_summary(): void
    {
        $rider = Rider::factory()->create();
        DailyParcelCount::factory()->create(['rider_id' => $rider->id, 'assigned' => 10, 'delivered' => 8, 'failed' => 1, 'returned' => 1]);

        $response = $this->getJson('/api/v1/reports/parcels');

        $response->assertOk()->assertJsonPath('data.0.success_rate', 80);
    }

    public function test_it_returns_empty_summary_when_no_data_matches_filters(): void
    {
        $response = $this->getJson('/api/v1/reports/attendance');

        $response->assertOk()->assertJsonCount(0, 'data');
    }
}
