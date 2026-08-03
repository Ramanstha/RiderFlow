<?php

namespace Database\Factories;

use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\RiderPerformance>
 */
class RiderPerformanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rider_id' => Rider::factory(),
            'period' => now()->startOfMonth()->toDateString(),
            'score' => fake()->numberBetween(0, 100),
            'deliveries_completed' => fake()->numberBetween(0, 200),
            'deliveries_failed' => fake()->numberBetween(0, 20),
            'attendance_rate' => fake()->randomFloat(2, 0, 100),
            'notes' => null,
        ];
    }
}
