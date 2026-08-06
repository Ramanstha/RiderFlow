<?php

namespace Database\Factories;

use App\Enums\RiderStatus;
use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Rider>
 */
class RiderFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'photo' => null,
            'license_number' => strtoupper(fake()->bothify('??-####-????')),
            'password' => static::$password ??= Hash::make('password'),
            'status' => RiderStatus::Active,
        ];
    }

    /**
     * Put the rider on a company's roster. Riders carry no company column, so
     * this is an assignment row rather than an attribute.
     */
    public function forCompany(Company $company): static
    {
        return $this->afterCreating(fn (Rider $rider) => RiderAssignment::factory()->create([
            'rider_id' => $rider->id,
            'company_id' => $company->id,
        ]));
    }

    /**
     * A rider who has not been given rider portal credentials.
     */
    public function withoutPortalAccess(): static
    {
        return $this->state(fn (array $attributes) => [
            'password' => null,
        ]);
    }
}
