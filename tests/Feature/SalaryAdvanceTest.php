<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Enums\SalaryAdvanceStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Models\SalaryAdvance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class SalaryAdvanceTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    public function test_rider_can_view_the_form_and_submit_a_request(): void
    {
        $rider = Rider::factory()->create(['pay' => 1000]);
        $this->markPresentToday($rider);

        $this->actingAs($rider, 'rider')->get('/rider-portal/advance/create')->assertOk()
            ->assertSee('Request Advance Salary')
            ->assertSee('Payable')
            ->assertSee('Rs. 1,000.00');

        $this->post('/rider-portal/advance', [
            'amount' => 800,
            'reason' => 'Medical expenses',
            'needed_by' => now()->addDays(3)->toDateString(),
            'rider_id' => 999,
        ])->assertRedirect(route('rider.advance.index'));

        $advance = SalaryAdvance::sole();
        $this->assertSame($rider->id, $advance->rider_id);
        $this->assertSame(SalaryAdvanceStatus::Pending, $advance->status);

        $this->get('/rider-portal/advance')->assertOk()->assertSee('Medical expenses');
    }

    public function test_amount_cannot_exceed_this_months_earnings(): void
    {
        $rider = Rider::factory()->create(['pay' => 500]);
        $this->markPresentToday($rider);

        $this->actingAs($rider, 'rider')->post('/rider-portal/advance', [
            'amount' => 600,
            'reason' => 'Rent',
        ])->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('salary_advances', 0);
    }

    public function test_approved_advances_reduce_what_can_be_requested(): void
    {
        $rider = Rider::factory()->create(['pay' => 500]);
        $this->markPresentToday($rider);
        $rider->salaryAdvances()->create(['amount' => 400, 'reason' => 'Earlier'])
            ->forceFill(['status' => SalaryAdvanceStatus::Approved, 'reviewed_at' => now()])->save();

        $this->actingAs($rider, 'rider')->post('/rider-portal/advance', [
            'amount' => 200,
            'reason' => 'Rent',
        ])->assertSessionHasErrors('amount');

        $this->post('/rider-portal/advance', [
            'amount' => 100,
            'reason' => 'Rent',
        ])->assertRedirect(route('rider.advance.index'));
    }

    public function test_form_is_disabled_when_nothing_is_payable(): void
    {
        $rider = Rider::factory()->create(['pay' => 500]);

        $this->actingAs($rider, 'rider')->get('/rider-portal/advance/create')->assertOk()
            ->assertSee('You have no payable amount this month yet');
    }

    protected function markPresentToday(Rider $rider): void
    {
        RiderAttendance::factory()->for($rider)->create([
            'date' => today()->toDateString(),
            'status' => AttendanceStatus::Present,
        ]);
    }

    public function test_rider_cannot_have_two_pending_requests(): void
    {
        $rider = Rider::factory()->create();
        $rider->salaryAdvances()->create(['amount' => 1000, 'reason' => 'First']);

        $this->actingAs($rider, 'rider')->post('/rider-portal/advance', [
            'amount' => 2000,
            'reason' => 'Second',
        ])->assertSessionHasErrors('amount');

        $this->assertDatabaseCount('salary_advances', 1);
    }

    public function test_rider_can_cancel_only_their_own_pending_request(): void
    {
        $rider = Rider::factory()->create();
        $other = Rider::factory()->create();
        $mine = $rider->salaryAdvances()->create(['amount' => 1000, 'reason' => 'Mine']);
        $theirs = $other->salaryAdvances()->create(['amount' => 1000, 'reason' => 'Theirs']);

        $this->actingAs($rider, 'rider');
        $this->delete("/rider-portal/advance/{$theirs->id}")->assertForbidden();
        $this->delete("/rider-portal/advance/{$mine->id}")->assertRedirect(route('rider.advance.index'));

        $this->assertModelMissing($mine);
        $this->assertModelExists($theirs);
    }

    public function test_manager_can_approve_a_pending_request(): void
    {
        $rider = Rider::factory()->create();
        $advance = $rider->salaryAdvances()->create(['amount' => 3000, 'reason' => 'School fees']);

        $user = $this->actingAsManager();
        $this->actingAs($user);

        $this->get('/advance')->assertOk()->assertSee('School fees');

        $this->put("/advance/{$advance->id}", [
            'status' => 'Approved',
            'admin_note' => 'Deduct next month',
        ])->assertRedirect(route('advance.index'));

        $advance->refresh();
        $this->assertSame(SalaryAdvanceStatus::Approved, $advance->status);
        $this->assertSame($user->id, $advance->reviewed_by);
        $this->assertNotNull($advance->reviewed_at);
    }

    public function test_viewer_cannot_review_requests(): void
    {
        $rider = Rider::factory()->create();
        $advance = $rider->salaryAdvances()->create(['amount' => 3000, 'reason' => 'Fees']);

        $this->actingAs($this->actingAsViewer());

        $this->get('/advance')->assertOk();
        $this->put("/advance/{$advance->id}", ['status' => 'Approved'])->assertForbidden();

        $this->assertSame(SalaryAdvanceStatus::Pending, $advance->fresh()->status);
    }
}
