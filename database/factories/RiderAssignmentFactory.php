<?php

namespace Database\Factories;

use App\Enums\AssignmentType;
use App\Models\Company;
use App\Models\Rider;
use App\Models\RiderAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiderAssignment>
 */
class RiderAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rider_id' => Rider::factory(),
            'company_id' => Company::factory(),
            'type' => fake()->randomElement(AssignmentType::cases()),
            'start_date' => now()->toDateString(),
            'end_date' => null,
        ];
    }
}
