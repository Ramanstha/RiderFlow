<?php

namespace Tests\Feature;

use App\Enums\CompanyStatus;
use App\Enums\RiderStatus;
use App\Models\Company;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Every account type signs in at /login and lands on its own dashboard.
 */
class UnifiedLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_login_page_is_the_single_front_door(): void
    {
        $this->get('/login')->assertOk()->assertSee('Sign in to your account');

        // The portals' old login URLs forward here.
        $this->get('/rider-portal/login')->assertRedirect('/login');
        $this->get('/client-portal/login')->assertRedirect('/login');
    }

    public function test_an_admin_lands_on_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['password' => 'admin-secret']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'admin-secret',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertGuest('rider');
        $this->assertGuest('client');
    }

    public function test_a_rider_lands_on_the_rider_dashboard(): void
    {
        $rider = Rider::factory()->create();

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'password',
        ])->assertRedirect(route('rider.dashboard'));

        $this->assertAuthenticatedAs($rider->fresh(), 'rider');
        $this->assertGuest('web');
        $this->assertGuest('client');
        $this->assertNotNull($rider->fresh()->last_login_at);
    }

    public function test_a_client_lands_on_the_client_dashboard(): void
    {
        $company = Company::factory()->create();

        $this->post('/login', [
            'email' => $company->email,
            'password' => 'password',
        ])->assertRedirect(route('client.dashboard'));

        $this->assertAuthenticatedAs($company->fresh(), 'client');
        $this->assertGuest('web');
        $this->assertGuest('rider');
        $this->assertNotNull($company->fresh()->last_login_at);
    }

    public function test_wrong_credentials_are_rejected_for_every_portal(): void
    {
        $rider = Rider::factory()->create();
        $company = Company::factory()->create();

        foreach ([$rider->email, $company->email, 'nobody@example.com'] as $email) {
            $this->post('/login', ['email' => $email, 'password' => 'wrong-password'])
                ->assertSessionHasErrors('email');
        }

        $this->assertGuest('web');
        $this->assertGuest('rider');
        $this->assertGuest('client');
    }

    public function test_an_account_without_a_password_cannot_sign_in(): void
    {
        $rider = Rider::factory()->withoutPortalAccess()->create();
        $company = Company::factory()->withoutPortalAccess()->create();

        $this->post('/login', ['email' => $rider->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->post('/login', ['email' => $company->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest('rider');
        $this->assertGuest('client');
    }

    public function test_an_inactive_rider_or_client_is_told_the_account_is_not_active(): void
    {
        $rider = Rider::factory()->create(['status' => RiderStatus::Inactive]);
        $company = Company::factory()->create(['status' => CompanyStatus::Inactive]);

        $this->post('/login', ['email' => $rider->email, 'password' => 'password'])
            ->assertSessionHasErrors(['email' => 'Your rider account is not active. Please contact your operations manager.']);

        $this->post('/login', ['email' => $company->email, 'password' => 'password'])
            ->assertSessionHasErrors(['email' => 'Your company account is not active. Please contact your account manager.']);

        $this->assertGuest('rider');
        $this->assertGuest('client');
    }

    public function test_an_admin_wins_when_the_same_email_exists_in_two_tables(): void
    {
        $shared = 'shared@example.com';

        $user = User::factory()->create(['email' => $shared, 'password' => 'shared-secret']);
        Rider::factory()->create(['email' => $shared, 'password' => 'shared-secret']);

        $this->post('/login', ['email' => $shared, 'password' => 'shared-secret'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertGuest('rider');
    }

    public function test_signing_in_to_one_portal_clears_another_portals_session(): void
    {
        $rider = Rider::factory()->create();
        $company = Company::factory()->create();

        $this->post('/login', ['email' => $rider->email, 'password' => 'password']);
        $this->assertAuthenticated('rider');

        // Already signed in, so the guest middleware sends them to their own home.
        $this->post('/login', ['email' => $company->email, 'password' => 'password'])
            ->assertRedirect(route('rider.dashboard'));

        $this->assertAuthenticated('rider');
        $this->assertGuest('client');
    }

    public function test_logout_clears_whichever_guard_is_active(): void
    {
        foreach ([
            [Rider::factory()->create(), 'rider'],
            [Company::factory()->create(), 'client'],
            [User::factory()->create(['password' => 'admin-secret']), 'web'],
        ] as [$account, $guard]) {
            $this->actingAs($account, $guard);
            $this->assertAuthenticated($guard);

            $this->post(route('logout'))->assertRedirect(route('login'));

            $this->assertGuest($guard);
        }
    }

    public function test_a_signed_in_rider_visiting_login_goes_to_the_rider_dashboard(): void
    {
        $this->actingAs(Rider::factory()->create(), 'rider')
            ->get('/login')->assertRedirect(route('rider.dashboard'));
    }

    public function test_a_signed_in_client_visiting_login_goes_to_the_client_dashboard(): void
    {
        $this->actingAs(Company::factory()->create(), 'client')
            ->get('/login')->assertRedirect(route('client.dashboard'));
    }

    public function test_a_signed_in_admin_visiting_login_goes_to_the_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create(), 'web')
            ->get('/login')->assertRedirect(route('dashboard'));
    }

    public function test_an_intended_url_from_another_portal_is_ignored(): void
    {
        $company = Company::factory()->create();

        // A guest hits a rider page, which stashes it as the intended URL.
        $this->get('/rider-portal/attendance')->assertRedirect(route('login'));

        // Signing in as a client must not follow that rider URL.
        $this->post('/login', ['email' => $company->email, 'password' => 'password'])
            ->assertRedirect(route('client.dashboard'));
    }

    public function test_an_intended_url_inside_the_matching_portal_is_honoured(): void
    {
        $company = Company::factory()->create();

        $this->get('/client-portal/parcel')->assertRedirect(route('login'));

        $this->post('/login', ['email' => $company->email, 'password' => 'password'])
            ->assertRedirect('http://localhost/client-portal/parcel');
    }
}
