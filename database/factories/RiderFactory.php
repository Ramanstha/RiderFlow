<?php

namespace Database\Factories;

use App\Enums\RiderStatus;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<\App\Models\Rider>
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
            'company_id' => Company::factory(),
            'performance' => fake()->numberBetween(0, 100),
        ];
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
