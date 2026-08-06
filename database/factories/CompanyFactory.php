<?php

namespace Database\Factories;

use App\Enums\CompanyStatus;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'contact_person' => fake()->name(),
            // Seeded companies can sign in to the client portal straight away.
            'password' => 'password',
            'status' => CompanyStatus::Active,
        ];
    }

    /**
     * A company that exists in the admin panel but cannot sign in yet.
     */
    public function withoutPortalAccess(): static
    {
        return $this->state(fn () => ['password' => null]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => CompanyStatus::Inactive]);
    }
}
