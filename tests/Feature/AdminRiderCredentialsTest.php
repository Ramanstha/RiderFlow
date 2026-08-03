<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Rider;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Admins issue rider portal credentials straight from the Add/Edit Rider forms.
 */
class AdminRiderCredentialsTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user);

        return $user;
    }

    /**
     * @return array<string, mixed>
     */
    protected function riderPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New Rider',
            'email' => 'new.rider@example.com',
            'phone' => '9800000000',
            'license_number' => 'LIC-0001',
            'status' => 'Active',
            'company_id' => Company::factory()->create()->id,
        ], $overrides);
    }

    public function test_the_add_rider_form_shows_the_portal_credential_fields(): void
    {
        $this->actingAsAdmin();

        $this->get(route('rider.create'))
            ->assertOk()
            ->assertSee('Rider Portal Credentials')
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false);
    }

    public function test_admin_sets_credentials_while_adding_a_rider_and_the_rider_can_sign_in(): void
    {
        $this->actingAsAdmin();

        $this->post(route('rider.store'), $this->riderPayload([
            'password' => 'rider-secret-1',
            'password_confirmation' => 'rider-secret-1',
        ]))->assertRedirect(route('rider.index'));

        $rider = Rider::query()->where('email', 'new.rider@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('rider-secret-1', $rider->password), 'The password must be stored hashed.');
        $this->assertNotSame('rider-secret-1', $rider->password);

        // Leave the admin session before signing in as the rider.
        $this->post(route('logout'));

        $this->post('/login', [
            'email' => 'new.rider@example.com',
            'password' => 'rider-secret-1',
        ])->assertRedirect(route('rider.dashboard'));

        $this->assertAuthenticatedAs($rider->fresh(), 'rider');
    }

    public function test_password_is_optional_when_adding_a_rider(): void
    {
        $this->actingAsAdmin();

        $this->post(route('rider.store'), $this->riderPayload())
            ->assertRedirect(route('rider.index'));

        $rider = Rider::query()->where('email', 'new.rider@example.com')->firstOrFail();

        $this->assertNull($rider->password);
        $this->assertFalse($rider->canAccessPortal());
    }

    public function test_a_mismatched_confirmation_is_rejected(): void
    {
        $this->actingAsAdmin();

        $this->post(route('rider.store'), $this->riderPayload([
            'password' => 'rider-secret-1',
            'password_confirmation' => 'something-else',
        ]))->assertSessionHasErrors('password');

        $this->assertDatabaseCount('riders', 0);
    }

    public function test_a_too_short_password_is_rejected(): void
    {
        $this->actingAsAdmin();

        $this->post(route('rider.store'), $this->riderPayload([
            'password' => 'short',
            'password_confirmation' => 'short',
        ]))->assertSessionHasErrors('password');

        $this->assertDatabaseCount('riders', 0);
    }

    public function test_editing_a_rider_without_touching_the_password_leaves_it_intact(): void
    {
        $this->actingAsAdmin();

        $rider = Rider::factory()->create();
        $existingHash = $rider->password;

        $this->put(route('rider.update', $rider), $this->riderPayload([
            'name' => 'Renamed Rider',
            'email' => $rider->email,
            'license_number' => $rider->license_number,
            'company_id' => $rider->company_id,
            'password' => '',
            'password_confirmation' => '',
        ]))->assertRedirect(route('rider.index'));

        $rider = $rider->fresh();

        $this->assertSame('Renamed Rider', $rider->name);
        $this->assertSame($existingHash, $rider->password, 'A blank password field must never wipe the credentials.');
    }

    public function test_admin_can_reset_an_existing_riders_password(): void
    {
        $this->actingAsAdmin();

        $rider = Rider::factory()->create();

        $this->put(route('rider.update', $rider), $this->riderPayload([
            'name' => $rider->name,
            'email' => $rider->email,
            'license_number' => $rider->license_number,
            'company_id' => $rider->company_id,
            'password' => 'reset-secret-9',
            'password_confirmation' => 'reset-secret-9',
        ]))->assertRedirect(route('rider.index'));

        $this->assertTrue(Hash::check('reset-secret-9', $rider->fresh()->password));

        // Leave the admin session before signing in as the rider.
        $this->post(route('logout'));

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'reset-secret-9',
        ])->assertRedirect(route('rider.dashboard'));
    }
}
