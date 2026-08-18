<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A client company assigns a day's parcel load to one of its own riders; the
 * rider then reports delivered against that figure.
 */
class ClientParcelAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;

    protected Rider $rider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();
        $this->rider = Rider::factory()->forCompany($this->company)->create();
    }

    public function test_a_client_assigns_parcels_to_its_own_rider(): void
    {
        $this->actingAs($this->company, 'client')
            ->post(route('client.parcel-assignment.store'), [
                'rider_id' => $this->rider->id,
                'date' => '2026-08-03',
                'assigned' => 40,
            ])->assertRedirect(route('client.parcel-assignment.index'));

        $assignment = DailyParcelCount::query()->where('rider_id', $this->rider->id)->firstOrFail();

        $this->assertSame(40, $assignment->assigned);
        $this->assertSame($this->company->id, $assignment->assigned_by_company_id);
        $this->assertNotNull($assignment->assigned_at);
        $this->assertTrue($assignment->isClientAssigned());

        // The rider starts with nothing reported.
        $this->assertSame(0, $assignment->delivered);
        $this->assertSame(40, $assignment->outstanding);
    }

    public function test_a_client_cannot_assign_to_another_companys_rider(): void
    {
        $theirRider = Rider::factory()->forCompany(Company::factory()->create())->create();

        $this->actingAs($this->company, 'client')
            ->post(route('client.parcel-assignment.store'), [
                'rider_id' => $theirRider->id,
                'date' => '2026-08-03',
                'assigned' => 40,
            ])->assertSessionHasErrors('rider_id');

        $this->assertDatabaseCount('daily_parcel_counts', 0);
    }

    public function test_assigning_twice_for_a_day_replaces_the_figure_rather_than_duplicating(): void
    {
        $this->actingAs($this->company, 'client');

        $this->post(route('client.parcel-assignment.store'), [
            'rider_id' => $this->rider->id, 'date' => '2026-08-03', 'assigned' => 40,
        ]);
        $this->post(route('client.parcel-assignment.store'), [
            'rider_id' => $this->rider->id, 'date' => '2026-08-03', 'assigned' => 55,
        ]);

        $this->assertDatabaseCount('daily_parcel_counts', 1);
        $this->assertSame(55, DailyParcelCount::query()->firstOrFail()->assigned);
    }

    public function test_a_client_can_take_over_a_day_the_rider_already_logged(): void
    {
        $existing = DailyParcelCount::factory()->create([
            'rider_id' => $this->rider->id,
            'date' => '2026-08-03',
            'assigned' => 10,
            'delivered' => 8,
        ]);

        $this->actingAs($this->company, 'client')
            ->post(route('client.parcel-assignment.store'), [
                'rider_id' => $this->rider->id, 'date' => '2026-08-03', 'assigned' => 30,
            ])->assertRedirect(route('client.parcel-assignment.index'));

        $existing->refresh();

        $this->assertSame(30, $existing->assigned);
        $this->assertSame($this->company->id, $existing->assigned_by_company_id);
        // The rider's reported numbers survive.
        $this->assertSame(8, $existing->delivered);
    }

    public function test_an_assignment_cannot_be_set_below_what_the_rider_reported(): void
    {
        $assignment = $this->assignment(['assigned' => 30, 'delivered' => 25]);

        $this->actingAs($this->company, 'client')
            ->put(route('client.parcel-assignment.update', $assignment), ['assigned' => 20])
            ->assertSessionHasErrors('assigned');

        $this->assertSame(30, $assignment->fresh()->assigned);
    }

    public function test_a_client_edits_its_own_assignment(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->company, 'client')
            ->put(route('client.parcel-assignment.update', $assignment), ['assigned' => 45])
            ->assertRedirect(route('client.parcel-assignment.index'));

        $this->assertSame(45, $assignment->fresh()->assigned);
    }

    public function test_a_client_cannot_touch_another_companys_assignment(): void
    {
        $other = Company::factory()->create();
        $theirRider = Rider::factory()->forCompany($other)->create();
        $theirs = DailyParcelCount::factory()->create([
            'rider_id' => $theirRider->id,
            'assigned_by_company_id' => $other->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($this->company, 'client');

        $this->get(route('client.parcel-assignment.edit', $theirs))->assertNotFound();
        $this->put(route('client.parcel-assignment.update', $theirs), ['assigned' => 5])->assertNotFound();
        $this->delete(route('client.parcel-assignment.destroy', $theirs))->assertNotFound();
    }

    public function test_withdrawing_an_untouched_assignment_removes_the_row(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->company, 'client')
            ->delete(route('client.parcel-assignment.destroy', $assignment))
            ->assertRedirect(route('client.parcel-assignment.index'));

        $this->assertDatabaseCount('daily_parcel_counts', 0);
    }

    public function test_withdrawing_a_reported_assignment_hands_the_row_back_to_the_rider(): void
    {
        $assignment = $this->assignment(['assigned' => 30, 'delivered' => 12]);

        $this->actingAs($this->company, 'client')
            ->delete(route('client.parcel-assignment.destroy', $assignment))
            ->assertRedirect(route('client.parcel-assignment.index'));

        $assignment->refresh();

        // Nothing the rider reported is lost.
        $this->assertSame(12, $assignment->delivered);
        $this->assertNull($assignment->assigned_by_company_id);
        $this->assertFalse($assignment->isClientAssigned());
    }

    public function test_the_index_only_lists_the_clients_own_assignments(): void
    {
        $this->assignment(['assigned' => 30]);

        $other = Company::factory()->create();
        $theirRider = Rider::factory()->forCompany($other)->create(['name' => 'Their Rider']);
        DailyParcelCount::factory()->create([
            'rider_id' => $theirRider->id,
            'assigned_by_company_id' => $other->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($this->company, 'client')
            ->get(route('client.parcel-assignment.index'))
            ->assertOk()
            ->assertSee($this->rider->name)
            ->assertDontSee('Their Rider');
    }

    public function test_a_rider_only_sees_a_client_assigned_row_they_own(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->rider, 'rider')
            ->get(route('rider.parcel.edit', $assignment))
            ->assertOk()
            ->assertSee('assigned you')
            ->assertSee($this->company->name);
    }

    public function test_a_rider_reports_against_a_client_assignment(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->rider, 'rider')
            ->put(route('rider.parcel.update', $assignment), [
                'date' => $assignment->date->toDateString(),
                'assigned' => 30,
                'delivered' => 25,
            ])->assertRedirect(route('rider.parcel.index'));

        $assignment->refresh();

        $this->assertSame(25, $assignment->delivered);
        $this->assertSame(30, $assignment->assigned);
        $this->assertSame($this->company->id, $assignment->assigned_by_company_id);
    }

    public function test_a_rider_cannot_change_the_assigned_figure_a_client_set(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->rider, 'rider')
            ->put(route('rider.parcel.update', $assignment), [
                'date' => $assignment->date->toDateString(),
                // Both are spoofed and must be ignored.
                'assigned' => 999,
                'delivered' => 10,
            ])->assertRedirect(route('rider.parcel.index'));

        $assignment->refresh();

        $this->assertSame(30, $assignment->assigned, 'A rider must not be able to move a client-assigned figure.');
        $this->assertSame(10, $assignment->delivered);
    }

    public function test_a_rider_cannot_move_the_date_a_client_set(): void
    {
        $assignment = $this->assignment(['assigned' => 30, 'date' => '2026-08-03']);

        $this->actingAs($this->rider, 'rider')
            ->put(route('rider.parcel.update', $assignment), [
                'date' => '2026-07-01',
                'assigned' => 30,
                'delivered' => 5,
            ])->assertRedirect(route('rider.parcel.index'));

        $this->assertSame('2026-08-03', $assignment->fresh()->date->toDateString());
    }

    public function test_a_rider_still_controls_their_own_self_logged_counts(): void
    {
        $own = DailyParcelCount::factory()->create([
            'rider_id' => $this->rider->id,
            'date' => now()->subDay()->toDateString(),
            'assigned' => 10,
        ]);

        $this->actingAs($this->rider, 'rider')
            ->put(route('rider.parcel.update', $own), [
                'date' => $own->date->toDateString(),
                'assigned' => 22,
                'delivered' => 20,
            ])->assertRedirect(route('rider.parcel.index'));

        $this->assertSame(22, $own->fresh()->assigned);
    }

    public function test_delivered_still_cannot_exceed_a_client_assigned_load(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->rider, 'rider')
            ->from(route('rider.parcel.edit', $assignment))
            ->put(route('rider.parcel.update', $assignment), [
                'date' => $assignment->date->toDateString(),
                'assigned' => 30,
                'delivered' => 45,
            ])->assertSessionHasErrors('delivered');

        $this->assertSame(0, $assignment->fresh()->delivered);
    }

    public function test_the_assignment_pages_render(): void
    {
        $assignment = $this->assignment(['assigned' => 30]);

        $this->actingAs($this->company, 'client');

        $this->get(route('client.parcel-assignment.index'))->assertOk();
        $this->get(route('client.parcel-assignment.create'))->assertOk();
        $this->get(route('client.parcel-assignment.edit', $assignment))->assertOk();
    }

    public function test_guests_and_other_guards_cannot_reach_the_assignment_pages(): void
    {
        $this->get(route('client.parcel-assignment.index'))->assertRedirect(route('login'));

        $this->actingAs($this->rider, 'rider')
            ->get(route('client.parcel-assignment.index'))->assertRedirect(route('login'));
    }

    /**
     * A parcel load already assigned by this company to this rider.
     */
    protected function assignment(array $attributes = []): DailyParcelCount
    {
        return DailyParcelCount::factory()->create([
            'rider_id' => $this->rider->id,
            'assigned_by_company_id' => $this->company->id,
            'assigned_at' => now(),
            'date' => now()->toDateString(),
            'delivered' => 0,
            ...$attributes,
        ]);
    }
}
