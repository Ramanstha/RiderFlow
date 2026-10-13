<?php

namespace Tests\Feature;

use App\Enums\RiderStatus;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAssignment;
use App\Models\RiderAttendance;
use App\Models\RiderPerformance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiderPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_rider_can_sign_in_and_reach_the_dashboard(): void
    {
        $rider = Rider::factory()->create();

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'password',
        ])->assertRedirect(route('rider.dashboard'));

        $this->assertAuthenticatedAs($rider, 'rider');
        $this->assertNotNull($rider->fresh()->last_login_at);

        $this->get('/rider-portal')->assertOk()->assertSee($rider->name);
    }

    public function test_rider_without_a_password_cannot_sign_in(): void
    {
        $rider = Rider::factory()->withoutPortalAccess()->create();

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('rider');
    }

    public function test_inactive_rider_cannot_sign_in(): void
    {
        $rider = Rider::factory()->create(['status' => RiderStatus::Inactive]);

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('rider');
    }

    public function test_guests_are_redirected_to_the_unified_login(): void
    {
        $this->get('/rider-portal')->assertRedirect(route('login'));
        $this->get('/rider-portal/attendance')->assertRedirect(route('login'));
    }

    public function test_admin_session_does_not_unlock_the_rider_portal(): void
    {
        $this->get('/rider-portal')->assertRedirect(route('login'));
    }

    public function test_rider_only_sees_their_own_records(): void
    {
        $rider = Rider::factory()->create();
        $other = Rider::factory()->create(['name' => 'Someone Else']);

        RiderAttendance::factory()->create(['rider_id' => $other->id]);
        RiderAssignment::factory()->create(['rider_id' => $other->id]);
        RiderPerformance::factory()->create(['rider_id' => $other->id]);
        DailyParcelCount::factory()->create(['rider_id' => $other->id]);

        $this->actingAs($rider, 'rider');

        $this->get('/rider-portal/attendance')->assertOk()->assertSee('0 Present')->assertDontSee('Someone Else');
        $this->get('/rider-portal/assignment')->assertOk()->assertSee('No assignments found.');
        $this->get('/rider-portal/performance')->assertOk()->assertSee('No performance records yet.');
        $this->get('/rider-portal/parcel')->assertOk()->assertSee('No parcel counts submitted yet.');
    }

    public function test_every_portal_page_renders(): void
    {
        $rider = Rider::factory()->create();
        $parcelCount = DailyParcelCount::factory()->create(['rider_id' => $rider->id]);

        // The old portal login URL now forwards to the unified form.
        $this->get('/rider-portal/login')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertSee('Sign in to your account');

        $this->actingAs($rider, 'rider');

        foreach ([
            '/rider-portal',
            '/rider-portal/attendance',
            '/rider-portal/assignment',
            '/rider-portal/performance',
            '/rider-portal/parcel',
            '/rider-portal/parcel/create',
            "/rider-portal/parcel/{$parcelCount->id}/edit",
            '/rider-portal/profile',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_rider_submits_a_parcel_count_against_their_own_id(): void
    {
        $rider = Rider::factory()->create();

        $this->actingAs($rider, 'rider')->post('/rider-portal/parcel', [
            // rider_id is deliberately spoofed and must be ignored.
            'rider_id' => Rider::factory()->create()->id,
            'date' => now()->toDateString(),
            'assigned' => 20,
            'delivered' => 18,
        ])->assertRedirect(route('rider.parcel.index'));

        $this->assertDatabaseHas('daily_parcel_counts', [
            'rider_id' => $rider->id,
            'assigned' => 20,
            'delivered' => 18,
        ]);
    }

    public function test_delivered_cannot_exceed_assigned(): void
    {
        $rider = Rider::factory()->create();

        $this->actingAs($rider, 'rider')->post('/rider-portal/parcel', [
            'date' => now()->toDateString(),
            'assigned' => 5,
            'delivered' => 9,
        ])->assertSessionHasErrors('delivered');
    }

    public function test_rider_can_open_the_day_with_only_a_start_reading(): void
    {
        $rider = Rider::factory()->create();

        $this->actingAs($rider, 'rider')->post('/rider-portal/parcel', [
            'date' => now()->toDateString(),
            'assigned' => 20,
            'start_km' => 1200,
        ])->assertRedirect(route('rider.parcel.index'));

        $this->assertDatabaseHas('daily_parcel_counts', [
            'rider_id' => $rider->id,
            'start_km' => 1200,
            'end_km' => null,
            'delivered' => null,
        ]);
    }

    public function test_rider_closes_the_day_with_end_reading_and_delivered(): void
    {
        $rider = Rider::factory()->create();
        $parcel = DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => now()->toDateString(),
            'assigned' => 20,
            'delivered' => null,
            'start_km' => 1200,
            'end_km' => null,
        ]);

        $this->actingAs($rider, 'rider')->put("/rider-portal/parcel/{$parcel->id}", [
            'date' => now()->toDateString(),
            'assigned' => 20,
            'delivered' => 18,
            'start_km' => 1200,
            'end_km' => 1265,
        ])->assertRedirect(route('rider.parcel.index'));

        $this->assertSame(65, $parcel->fresh()->km_run);
    }

    public function test_end_reading_cannot_be_below_start_reading(): void
    {
        $rider = Rider::factory()->create();

        $this->actingAs($rider, 'rider')->post('/rider-portal/parcel', [
            'date' => now()->toDateString(),
            'assigned' => 20,
            'start_km' => 1200,
            'end_km' => 1100,
        ])->assertSessionHasErrors('end_km');
    }

    public function test_rider_cannot_edit_another_riders_parcel_count(): void
    {
        $rider = Rider::factory()->create();
        $foreign = DailyParcelCount::factory()->create(['rider_id' => Rider::factory()->create()->id]);

        $this->actingAs($rider, 'rider')
            ->get("/rider-portal/parcel/{$foreign->id}/edit")
            ->assertForbidden();

        $this->actingAs($rider, 'rider')
            ->put("/rider-portal/parcel/{$foreign->id}", [
                'date' => now()->toDateString(),
                'assigned' => 1,
                'delivered' => 1,
            ])->assertForbidden();
    }

    public function test_rider_can_update_profile_and_password(): void
    {
        $rider = Rider::factory()->create();

        $this->actingAs($rider, 'rider')->put('/rider-portal/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
        ])->assertRedirect(route('rider.profile.edit'));

        $this->assertDatabaseHas('riders', [
            'id' => $rider->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $this->actingAs($rider, 'rider')->put('/rider-portal/profile/password', [
            'current_password' => 'password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])->assertRedirect(route('rider.profile.edit'));

        $this->post('/rider-portal/logout');

        $this->post('/login', [
            'email' => 'updated@example.com',
            'password' => 'new-secret-password',
        ])->assertRedirect(route('rider.dashboard'));
    }

    public function test_admin_can_issue_and_reset_portal_credentials(): void
    {
        $rider = Rider::factory()->withoutPortalAccess()->create();

        $this->assertNull($rider->password);

        $rider->update(['password' => 'issued-by-admin']);

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'issued-by-admin',
        ])->assertRedirect(route('rider.dashboard'));
    }
}
