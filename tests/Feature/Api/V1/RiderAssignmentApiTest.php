<?php

namespace Tests\Feature\Api\V1;

use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class RiderAssignmentApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_creates_an_assignment(): void
    {
        $rider = Rider::factory()->create();
        $company = Company::factory()->create();

        $response = $this->postJson('/api/v1/assignments', [
            'rider_id' => $rider->id,
            'company_id' => $company->id,
            'start_date' => '2026-07-01',
            'status' => 'Assigned',
        ]);

        $response->assertCreated()->assertJsonPath('data.status', 'Assigned');
    }

    public function test_it_rejects_overlapping_active_assignments_for_the_same_rider(): void
    {
        $rider = Rider::factory()->create();
        $company = Company::factory()->create();

        RiderAssignment::factory()->create([
            'rider_id' => $rider->id,
            'company_id' => $company->id,
            'start_date' => '2026-07-01',
            'end_date' => null,
            'status' => 'Assigned',
        ]);

        $response = $this->postJson('/api/v1/assignments', [
            'rider_id' => $rider->id,
            'company_id' => $company->id,
            'start_date' => '2026-07-10',
            'status' => 'Assigned',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['start_date']);
    }
}
