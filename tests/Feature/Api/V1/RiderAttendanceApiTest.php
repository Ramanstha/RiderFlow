<?php

namespace Tests\Feature\Api\V1;

use App\Models\Rider;
use App\Models\RiderAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class RiderAttendanceApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_records_attendance(): void
    {
        $rider = Rider::factory()->create();

        $response = $this->postJson('/api/v1/attendances', [
            'rider_id' => $rider->id,
            'date' => '2026-07-01',
            'status' => 'Present',
            'check_in' => '09:00',
            'check_out' => '17:00',
        ]);

        $response->assertCreated()->assertJsonPath('data.status', 'Present');
    }

    public function test_it_rejects_duplicate_attendance_for_same_rider_and_date(): void
    {
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id, 'date' => '2026-07-01']);

        $response = $this->postJson('/api/v1/attendances', [
            'rider_id' => $rider->id,
            'date' => '2026-07-01',
            'status' => 'Present',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['date']);
    }

    public function test_it_lists_and_filters_attendance_by_rider(): void
    {
        $rider = Rider::factory()->create();
        RiderAttendance::factory()->count(2)->create(['rider_id' => $rider->id]);
        RiderAttendance::factory()->count(3)->create();

        $response = $this->getJson("/api/v1/attendances?rider_id={$rider->id}");

        $response->assertOk()->assertJsonCount(2, 'data');
    }
}
