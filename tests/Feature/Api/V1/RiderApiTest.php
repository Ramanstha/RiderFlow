<?php

namespace Tests\Feature\Api\V1;

use App\Models\Company;
use App\Models\Rider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class RiderApiTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsSuperAdmin();
    }

    public function test_it_lists_riders_with_company_relation_loaded(): void
    {
        $company = Company::factory()->create();
        Rider::factory()->count(2)->create(['company_id' => $company->id]);

        $response = $this->getJson('/api/v1/riders');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.company.id', $company->id);
    }

    public function test_it_creates_a_rider(): void
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/v1/riders', [
            'name' => 'Jane Rider',
            'email' => 'jane.rider@example.com',
            'phone' => '1234567890',
            'license_number' => 'LIC-0001',
            'status' => 'Active',
            'company_id' => $company->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Jane Rider')
            ->assertJsonPath('data.company.id', $company->id);

        $this->assertDatabaseHas('riders', ['email' => 'jane.rider@example.com']);
    }

    public function test_it_rejects_duplicate_email_and_license_number(): void
    {
        $company = Company::factory()->create();
        $existing = Rider::factory()->create(['company_id' => $company->id]);

        $response = $this->postJson('/api/v1/riders', [
            'name' => 'Duplicate Rider',
            'email' => $existing->email,
            'phone' => '1234567890',
            'license_number' => $existing->license_number,
            'status' => 'Active',
            'company_id' => $company->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'license_number']);
    }

    public function test_it_rejects_missing_company_id(): void
    {
        $response = $this->postJson('/api/v1/riders', [
            'name' => 'No Company Rider',
            'email' => 'nocompany@example.com',
            'phone' => '1234567890',
            'license_number' => 'LIC-0002',
            'status' => 'Active',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['company_id']);
    }

    public function test_it_updates_a_rider(): void
    {
        $company = Company::factory()->create();
        $rider = Rider::factory()->create(['company_id' => $company->id]);

        $response = $this->putJson("/api/v1/riders/{$rider->id}", [
            'name' => 'Updated Name',
            'email' => $rider->email,
            'phone' => $rider->phone,
            'license_number' => $rider->license_number,
            'status' => 'Inactive',
            'company_id' => $company->id,
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'Updated Name');
        $this->assertDatabaseHas('riders', ['id' => $rider->id, 'status' => 'Inactive']);
    }

    public function test_it_deletes_a_rider_and_removes_photo_from_storage(): void
    {
        Storage::fake('public');

        $company = Company::factory()->create();
        $photo = UploadedFile::fake()->image('rider.jpg');
        $path = $photo->store('riders', 'public');

        $rider = Rider::factory()->create(['company_id' => $company->id, 'photo' => $path]);

        $response = $this->deleteJson("/api/v1/riders/{$rider->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('riders', ['id' => $rider->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_show_returns_404_for_unknown_rider(): void
    {
        $response = $this->getJson('/api/v1/riders/999999');

        $response->assertStatus(404);
    }

}
