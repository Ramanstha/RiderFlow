<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAssignment;
use App\Models\RiderAttendance;
use App\Models\RiderPerformance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_unified_login(): void
    {
        $this->get('/client-portal')->assertRedirect(route('login'));
        $this->get('/client-portal/rider')->assertRedirect(route('login'));
        $this->get('/client-portal/reports')->assertRedirect(route('login'));
    }

    public function test_an_admin_session_does_not_unlock_the_client_portal(): void
    {
        $this->actingAs(User::factory()->create(), 'web')
            ->get('/client-portal')->assertRedirect(route('login'));
    }

    public function test_a_rider_session_does_not_unlock_the_client_portal(): void
    {
        $this->actingAs(Rider::factory()->create(), 'rider')
            ->get('/client-portal')->assertRedirect(route('login'));
    }

    public function test_every_portal_page_renders(): void
    {
        $company = Company::factory()->create();
        $rider = Rider::factory()->forCompany($company)->create();

        // Separate days on purpose: the parcel row marks its own day's
        // attendance through the observer, so a random attendance date could
        // otherwise land on the same day and break the rider/date unique key.
        DailyParcelCount::factory()->create(['rider_id' => $rider->id, 'date' => now()->subDays(2)]);
        RiderAttendance::factory()->create(['rider_id' => $rider->id, 'date' => now()->subDay()]);
        RiderPerformance::factory()->create(['rider_id' => $rider->id]);

        $this->actingAs($company, 'client');

        foreach ([
            '/client-portal',
            '/client-portal/rider',
            "/client-portal/rider/{$rider->id}",
            '/client-portal/attendance',
            '/client-portal/parcel',
            '/client-portal/assignment',
            '/client-portal/performance',
            '/client-portal/reports',
            '/client-portal/reports/attendance',
            '/client-portal/reports/parcels',
            '/client-portal/reports/performance',
            '/client-portal/profile',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_an_open_day_without_delivered_or_end_reading_renders(): void
    {
        $company = Company::factory()->create();
        $rider = Rider::factory()->forCompany($company)->create();

        DailyParcelCount::factory()->create([
            'rider_id' => $rider->id,
            'date' => now(),
            'assigned' => 20,
            'delivered' => null,
            'start_km' => 1200,
            'end_km' => null,
        ]);

        $this->actingAs($company, 'client');

        $this->get('/client-portal/parcel')->assertOk()->assertSee('1,200');
        $this->get("/client-portal/rider/{$rider->id}")->assertOk();
        $this->get('/client-portal/parcel-assignment')->assertOk();
    }

    public function test_the_dashboard_counts_only_the_clients_own_fleet(): void
    {
        $company = Company::factory()->create();
        $other = Company::factory()->create();

        $mine = Rider::factory()->forCompany($company)->create(['name' => 'My Rider']);
        $theirs = Rider::factory()->forCompany($other)->create(['name' => 'Their Rider']);

        DailyParcelCount::factory()->create(['rider_id' => $mine->id, 'date' => now(), 'assigned' => 10, 'delivered' => 9]);
        DailyParcelCount::factory()->create(['rider_id' => $theirs->id, 'date' => now(), 'assigned' => 500, 'delivered' => 500]);

        $this->actingAs($company, 'client')
            ->get('/client-portal')
            ->assertOk()
            ->assertSee('My Rider')
            ->assertDontSee('Their Rider')
            ->assertSee('9 delivered')
            ->assertDontSee('500 delivered');
    }

    public function test_a_client_only_sees_its_own_riders(): void
    {
        $company = Company::factory()->create();
        $other = Company::factory()->create();

        Rider::factory()->forCompany($company)->create(['name' => 'My Rider']);
        Rider::factory()->forCompany($other)->create(['name' => 'Their Rider']);

        $this->actingAs($company, 'client')
            ->get('/client-portal/rider')
            ->assertOk()
            ->assertSee('My Rider')
            ->assertDontSee('Their Rider');
    }

    public function test_a_client_cannot_open_another_companys_rider(): void
    {
        $company = Company::factory()->create();
        $theirRider = Rider::factory()->forCompany(Company::factory()->create())->create();

        $this->actingAs($company, 'client')
            ->get("/client-portal/rider/{$theirRider->id}")
            ->assertNotFound();
    }

    public function test_attendance_and_parcel_lists_are_scoped_to_the_client(): void
    {
        $company = Company::factory()->create();
        $other = Company::factory()->create();

        $theirRider = Rider::factory()->forCompany($other)->create(['name' => 'Their Rider']);
        RiderAttendance::factory()->create(['rider_id' => $theirRider->id]);
        DailyParcelCount::factory()->create(['rider_id' => $theirRider->id]);
        RiderAssignment::factory()->create(['rider_id' => $theirRider->id, 'company_id' => $other->id]);

        $this->actingAs($company, 'client');

        $this->get('/client-portal/attendance')->assertOk()->assertSee('No attendance records found.');
        $this->get('/client-portal/parcel')->assertOk()->assertSee('No parcel records found.');
        $this->get('/client-portal/assignment')->assertOk()->assertSee('No assignments found.');
    }

    public function test_a_client_cannot_widen_a_report_to_another_company(): void
    {
        $company = Company::factory()->create();
        $other = Company::factory()->create();

        $theirRider = Rider::factory()->forCompany($other)->create(['name' => 'Their Rider']);
        RiderAttendance::factory()->create(['rider_id' => $theirRider->id]);

        // Both a spoofed company_id and a rider from another company must be ignored.
        $this->actingAs($company, 'client')
            ->get("/client-portal/reports/attendance?company_id={$other->id}&rider_id={$theirRider->id}")
            ->assertOk()
            ->assertDontSee('Their Rider')
            ->assertSee('No records for this period.');
    }

    public function test_report_exports_are_scoped_to_the_client(): void
    {
        $company = Company::factory()->create();
        $other = Company::factory()->create();

        $mine = Rider::factory()->forCompany($company)->create(['name' => 'My Rider']);
        $theirs = Rider::factory()->forCompany($other)->create(['name' => 'Their Rider']);

        RiderAttendance::factory()->create(['rider_id' => $mine->id]);
        RiderAttendance::factory()->create(['rider_id' => $theirs->id]);

        $response = $this->actingAs($company, 'client')
            ->get("/client-portal/reports/attendance/export?company_id={$other->id}");

        $response->assertOk();

        $csv = $this->streamed($response);

        $this->assertStringContainsString('My Rider', $csv);
        $this->assertStringNotContainsString('Their Rider', $csv);
    }

    public function test_every_export_format_downloads(): void
    {
        $company = Company::factory()->create();
        $rider = Rider::factory()->forCompany($company)->create();
        RiderAttendance::factory()->create(['rider_id' => $rider->id]);
        DailyParcelCount::factory()->create(['rider_id' => $rider->id]);
        RiderPerformance::factory()->create(['rider_id' => $rider->id]);

        $this->actingAs($company, 'client');

        foreach (['attendance', 'parcels', 'performance'] as $report) {
            foreach (['export', 'export/excel', 'export/pdf'] as $format) {
                $this->get("/client-portal/reports/{$report}/{$format}")->assertOk();
            }
        }
    }

    public function test_a_client_updates_its_contact_details_and_password(): void
    {
        $company = Company::factory()->create();

        $this->actingAs($company, 'client')
            ->put('/client-portal/profile', [
                'email' => 'updated@example.com',
                'phone' => '9812345678',
                'address' => 'New Street 12',
                'contact_person' => 'New Person',
            ])->assertRedirect(route('client.profile.edit'));

        $company = $company->fresh();
        $this->assertSame('updated@example.com', $company->email);
        $this->assertSame('New Person', $company->contact_person);

        $this->put('/client-portal/profile/password', [
            'current_password' => 'password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])->assertRedirect(route('client.profile.edit'));

        $this->assertTrue(Hash::check('new-secret-password', $company->fresh()->password));

        $this->post(route('logout'));

        $this->post('/login', [
            'email' => 'updated@example.com',
            'password' => 'new-secret-password',
        ])->assertRedirect(route('client.dashboard'));
    }

    public function test_a_client_cannot_rename_itself_or_change_its_status(): void
    {
        $company = Company::factory()->create(['name' => 'Original Name']);

        $this->actingAs($company, 'client')
            ->put('/client-portal/profile', [
                'email' => $company->email,
                'phone' => '9812345678',
                'contact_person' => 'Someone',
                // Neither of these is in the allowed rule set.
                'name' => 'Renamed Inc',
                'status' => 'Inactive',
            ])->assertRedirect(route('client.profile.edit'));

        $company = $company->fresh();
        $this->assertSame('Original Name', $company->name);
        $this->assertSame('Active', $company->status->value);
    }

    /**
     * Capture the body of a streamed download.
     */
    protected function streamed($response): string
    {
        ob_start();
        $response->baseResponse->sendContent();

        return ob_get_clean();
    }
}
