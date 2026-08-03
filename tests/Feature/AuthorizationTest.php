<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    public function test_manager_cannot_manage_companies_but_can_manage_riders(): void
    {
        $this->actingAsManager();

        $this->postJson('/api/v1/companies', [
            'name' => 'Blocked Co',
            'status' => 'Active',
        ])->assertStatus(403);

        $company = Company::factory()->create();

        $this->postJson('/api/v1/riders', [
            'name' => 'Allowed Rider',
            'email' => 'allowed@example.com',
            'phone' => '1234567890',
            'license_number' => 'LIC-9999',
            'status' => 'Active',
            'company_id' => $company->id,
        ])->assertStatus(201);
    }

    public function test_viewer_can_read_but_not_write_riders(): void
    {
        $this->actingAsViewer();

        $this->getJson('/api/v1/riders')->assertOk();

        $company = Company::factory()->create();

        $this->postJson('/api/v1/riders', [
            'name' => 'Blocked Rider',
            'email' => 'blocked@example.com',
            'phone' => '1234567890',
            'license_number' => 'LIC-8888',
            'status' => 'Active',
            'company_id' => $company->id,
        ])->assertStatus(403);
    }

    public function test_unauthenticated_web_request_redirects_to_login(): void
    {
        $this->get('/rider')->assertRedirect(route('login'));
    }

    public function test_login_page_is_publicly_accessible(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_valid_login_redirects_to_dashboard(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = \App\Models\User::factory()->create(['password' => 'password']);
        $user->assignRole('Super Admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_login_shows_error(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = \App\Models\User::factory()->create(['password' => 'password']);
        $user->assignRole('Super Admin');

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_non_super_admin_cannot_reach_user_management(): void
    {
        $manager = $this->actingAsManager();

        $this->actingAs($manager)->get('/user')->assertStatus(403);
    }

    public function test_super_admin_can_reach_user_management(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->get('/user')->assertOk();
    }
}
