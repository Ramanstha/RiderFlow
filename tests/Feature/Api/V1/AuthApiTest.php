<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_in_with_valid_credentials_and_returns_a_token(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_it_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_requests_to_protected_routes_are_rejected(): void
    {
        $response = $this->getJson('/api/v1/riders');

        $response->assertStatus(401);
    }
}
