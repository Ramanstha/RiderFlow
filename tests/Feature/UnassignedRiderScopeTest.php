<?php

namespace Tests\Feature;

use App\Enums\RiderStatus;
use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The assignment form may only offer active riders nobody has claimed yet.
 */
class UnassignedRiderScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_skips_riders_with_a_running_or_upcoming_assignment(): void
    {
        $company = Company::factory()->create();

        Rider::factory()->create(['name' => 'Free']);
        $openEnded = Rider::factory()->create(['name' => 'OpenEnded']);
        $futureEnd = Rider::factory()->create(['name' => 'FutureEnd']);
        $expired = Rider::factory()->create(['name' => 'Expired']);

        RiderAssignment::factory()->create([
            'rider_id' => $openEnded->id, 'company_id' => $company->id,
            'start_date' => now()->subMonth()->toDateString(), 'end_date' => null,
        ]);
        RiderAssignment::factory()->create([
            'rider_id' => $futureEnd->id, 'company_id' => $company->id,
            'start_date' => now()->subMonth()->toDateString(), 'end_date' => now()->addWeek()->toDateString(),
        ]);
        RiderAssignment::factory()->create([
            'rider_id' => $expired->id, 'company_id' => $company->id,
            'start_date' => now()->subMonths(2)->toDateString(), 'end_date' => now()->subDay()->toDateString(),
        ]);

        $names = Rider::query()->unassigned()->pluck('name')->all();

        $this->assertEqualsCanonicalizing(['Free', 'Expired'], $names);
    }

    public function test_active_scope_drops_inactive_riders(): void
    {
        Rider::factory()->create(['name' => 'OnRoster', 'status' => RiderStatus::Active]);
        Rider::factory()->create(['name' => 'OffRoster', 'status' => RiderStatus::Inactive]);

        $names = Rider::query()->active()->pluck('name')->all();

        $this->assertSame(['OnRoster'], $names);
    }

    public function test_the_create_form_lists_only_active_unassigned_riders(): void
    {
        $company = Company::factory()->create();

        Rider::factory()->create(['name' => 'PickMe', 'status' => RiderStatus::Active]);
        Rider::factory()->create(['name' => 'InactiveAndFree', 'status' => RiderStatus::Inactive]);
        $busy = Rider::factory()->create(['name' => 'ActiveButBusy', 'status' => RiderStatus::Active]);

        RiderAssignment::factory()->create([
            'rider_id' => $busy->id, 'company_id' => $company->id,
            'start_date' => now()->subMonth()->toDateString(), 'end_date' => null,
        ]);

        $riders = Rider::query()->active()->unassigned()->orderBy('name')->pluck('name')->all();

        $this->assertSame(['PickMe'], $riders);
    }
}
