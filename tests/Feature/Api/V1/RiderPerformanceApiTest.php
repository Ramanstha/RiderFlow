<?php

namespace Tests\Feature\Api\V1;

use App\Models\Rider;
use App\Models\RiderPerformance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class RiderPerformanceApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_creates_a_performance_record(): void
    {
        $rider = Rider::factory()->create();

        $response = $this->postJson('/api/v1/performances', [
            'rider_id' => $rider->id,
            'period' => '2026-07-01',
            'score' => 85,
            'deliveries_completed' => 100,
            'deliveries_failed' => 5,
        ]);

        $response->assertCreated()->assertJsonPath('data.score', 85);
    }

    public function test_it_rejects_duplicate_period_for_same_rider(): void
    {
        $rider = Rider::factory()->create();
        RiderPerformance::factory()->create(['rider_id' => $rider->id, 'period' => '2026-07-01']);

        $response = $this->postJson('/api/v1/performances', [
            'rider_id' => $rider->id,
            'period' => '2026-07-15',
            'score' => 90,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['period']);
    }
}
