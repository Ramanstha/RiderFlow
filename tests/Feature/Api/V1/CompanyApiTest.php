<?php

namespace Tests\Feature\Api\V1;

use App\Models\Company;
use App\Models\Rider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_lists_companies(): void
    {
        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/companies');

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_company(): void
    {
        $response = $this->postJson('/api/v1/companies', [
            'name' => 'Acme Logistics',
            'status' => 'Active',
        ]);

        $response->assertCreated()->assertJsonPath('data.name', 'Acme Logistics');
        $this->assertDatabaseHas('companies', ['name' => 'Acme Logistics']);
    }

    public function test_it_rejects_duplicate_name(): void
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/v1/companies', [
            'name' => $company->name,
            'status' => 'Active',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    public function test_it_updates_a_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->putJson("/api/v1/companies/{$company->id}", [
            'name' => 'Updated Co',
            'status' => 'Inactive',
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'Updated Co');
    }

    public function test_it_prevents_deleting_a_company_with_riders(): void
    {
        $company = Company::factory()->create();
        Rider::factory()->forCompany($company)->create();

        $response = $this->deleteJson("/api/v1/companies/{$company->id}");

        $response->assertStatus(422);
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }

    public function test_it_deletes_a_company_without_riders(): void
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/v1/companies/{$company->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
