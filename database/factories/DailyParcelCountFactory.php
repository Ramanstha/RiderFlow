<?php

namespace Database\Factories;

use App\Models\DailyParcelCount;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyParcelCount>
 */
class DailyParcelCountFactory extends Factory
{
    public function definition(): array
    {
        $assigned = fake()->numberBetween(10, 50);
        $delivered = fake()->numberBetween(max(0, $assigned - 8), $assigned);

        return [
            'rider_id' => Rider::factory(),
            'date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'assigned' => $assigned,
            'delivered' => $delivered,
        ];
    }
}
