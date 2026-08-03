<?php

namespace Tests\Feature\Api\V1;

use App\Models\DailyParcelCount;
use App\Models\Rider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class ParcelCountApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_records_a_parcel_count(): void
    {
        $rider = Rider::factory()->create();

        $response = $this->postJson('/api/v1/parcel-counts', [
            'rider_id' => $rider->id,
            'date' => '2026-07-01',
            'assigned' => 20,
            'delivered' => 18,
            'failed' => 1,
            'returned' => 1,
        ]);

        $response->assertCreated()->assertJsonPath('data.success_rate', 90);
    }

    public function test_it_rejects_duplicate_parcel_count_for_same_rider_and_date(): void
    {
        $rider = Rider::factory()->create();
        DailyParcelCount::factory()->create(['rider_id' => $rider->id, 'date' => '2026-07-01']);

        $response = $this->postJson('/api/v1/parcel-counts', [
            'rider_id' => $rider->id,
            'date' => '2026-07-01',
            'assigned' => 10,
            'delivered' => 10,
            'failed' => 0,
            'returned' => 0,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['date']);
    }
}
