<?php

namespace Tests\Feature;

use App\Models\Rider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IssueRiderPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_issues_passwords_only_to_riders_without_one(): void
    {
        $without = Rider::factory()->withoutPortalAccess()->create();
        $with = Rider::factory()->create();
        $existingHash = $with->password;

        $this->artisan('riders:issue-password --all')->assertSuccessful();

        $this->assertNotNull($without->fresh()->password);
        $this->assertSame($existingHash, $with->fresh()->password, 'An existing password must not be reset without --force.');
    }

    public function test_force_resets_riders_that_already_have_a_password(): void
    {
        $rider = Rider::factory()->create();
        $existingHash = $rider->password;

        $this->artisan('riders:issue-password --all --force')->assertSuccessful();

        $this->assertNotSame($existingHash, $rider->fresh()->password);
    }

    public function test_it_issues_a_chosen_password_to_a_single_rider_who_can_then_sign_in(): void
    {
        $rider = Rider::factory()->withoutPortalAccess()->create();

        $this->artisan("riders:issue-password {$rider->email} --password=chosen-secret")->assertSuccessful();

        $this->assertTrue(Hash::check('chosen-secret', $rider->fresh()->password));

        $this->post('/login', [
            'email' => $rider->email,
            'password' => 'chosen-secret',
        ])->assertRedirect(route('rider.dashboard'));

        $this->assertAuthenticatedAs($rider->fresh(), 'rider');
    }

    public function test_it_fails_for_an_unknown_email(): void
    {
        $this->artisan('riders:issue-password nobody@example.com')->assertSuccessful();

        $this->assertDatabaseCount('riders', 0);
    }

    public function test_it_requires_an_email_or_the_all_flag(): void
    {
        $rider = Rider::factory()->withoutPortalAccess()->create();

        $this->artisan('riders:issue-password')->assertSuccessful();

        $this->assertNull($rider->fresh()->password);
    }
}
