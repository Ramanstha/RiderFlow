<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Admins issue client portal credentials from the Add/Edit Company forms, or
 * in bulk with the clients:issue-password command.
 */
class AdminClientCredentialsTest extends TestCase
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
    protected function companyPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New Client Ltd',
            'email' => 'new.client@example.com',
            'phone' => '9800000000',
            'address' => 'Some Street 1',
            'contact_person' => 'Jane Doe',
            'status' => 'Active',
        ], $overrides);
    }

    public function test_the_add_company_form_shows_the_portal_credential_fields(): void
    {
        $this->actingAsAdmin();

        $this->get(route('company.create'))
            ->assertOk()
            ->assertSee('Client Portal Credentials')
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false);
    }

    public function test_admin_sets_credentials_while_adding_a_company_and_the_client_can_sign_in(): void
    {
        $this->actingAsAdmin();

        $this->post(route('company.store'), $this->companyPayload([
            'password' => 'client-secret-1',
            'password_confirmation' => 'client-secret-1',
        ]))->assertRedirect(route('company.index'));

        $company = Company::query()->where('email', 'new.client@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('client-secret-1', $company->password), 'The password must be stored hashed.');

        $this->post(route('logout'));

        $this->post('/login', [
            'email' => 'new.client@example.com',
            'password' => 'client-secret-1',
        ])->assertRedirect(route('client.dashboard'));

        $this->assertAuthenticatedAs($company->fresh(), 'client');
    }

    public function test_password_is_optional_when_adding_a_company(): void
    {
        $this->actingAsAdmin();

        $this->post(route('company.store'), $this->companyPayload())
            ->assertRedirect(route('company.index'));

        $company = Company::query()->where('email', 'new.client@example.com')->firstOrFail();

        $this->assertNull($company->password);
        $this->assertFalse($company->canAccessPortal());
    }

    public function test_a_password_without_an_email_is_rejected(): void
    {
        $this->actingAsAdmin();

        $this->post(route('company.store'), $this->companyPayload([
            'email' => '',
            'password' => 'client-secret-1',
            'password_confirmation' => 'client-secret-1',
        ]))->assertSessionHasErrors('email');

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_a_mismatched_confirmation_is_rejected(): void
    {
        $this->actingAsAdmin();

        $this->post(route('company.store'), $this->companyPayload([
            'password' => 'client-secret-1',
            'password_confirmation' => 'something-else',
        ]))->assertSessionHasErrors('password');

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_editing_a_company_without_touching_the_password_leaves_it_intact(): void
    {
        $this->actingAsAdmin();

        $company = Company::factory()->create();
        $existingHash = $company->password;

        $this->put(route('company.update', $company), $this->companyPayload([
            'name' => 'Renamed Client',
            'email' => $company->email,
            'password' => '',
            'password_confirmation' => '',
        ]))->assertRedirect(route('company.index'));

        $company = $company->fresh();

        $this->assertSame('Renamed Client', $company->name);
        $this->assertSame($existingHash, $company->password, 'A blank password field must never wipe the credentials.');
    }

    public function test_admin_can_reset_an_existing_clients_password(): void
    {
        $this->actingAsAdmin();

        $company = Company::factory()->create();

        $this->put(route('company.update', $company), $this->companyPayload([
            'name' => $company->name,
            'email' => $company->email,
            'password' => 'reset-secret-9',
            'password_confirmation' => 'reset-secret-9',
        ]))->assertRedirect(route('company.index'));

        $this->assertTrue(Hash::check('reset-secret-9', $company->fresh()->password));
    }

    public function test_the_command_issues_passwords_only_to_companies_without_one(): void
    {
        $without = Company::factory()->withoutPortalAccess()->create();
        $with = Company::factory()->create();
        $existingHash = $with->password;

        $this->artisan('clients:issue-password --all')->assertSuccessful();

        $this->assertNotNull($without->fresh()->password);
        $this->assertSame($existingHash, $with->fresh()->password);
    }

    public function test_the_command_skips_a_company_with_no_email(): void
    {
        $company = Company::factory()->withoutPortalAccess()->create(['email' => null]);

        $this->artisan('clients:issue-password --all')
            ->expectsOutputToContain('it has no email address to sign in with')
            ->assertSuccessful();

        $this->assertNull($company->fresh()->password);
    }

    public function test_the_command_issues_a_chosen_password_to_one_company(): void
    {
        $company = Company::factory()->withoutPortalAccess()->create();

        $this->artisan("clients:issue-password {$company->email} --password=chosen-secret")->assertSuccessful();

        $this->assertTrue(Hash::check('chosen-secret', $company->fresh()->password));

        $this->post('/login', [
            'email' => $company->email,
            'password' => 'chosen-secret',
        ])->assertRedirect(route('client.dashboard'));
    }
}
