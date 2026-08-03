<?php

namespace Database\Factories;

use App\Enums\AssignmentShift;
use App\Enums\AssignmentStatus;
use App\Models\Company;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\RiderAssignment>
 */
class RiderAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rider_id' => Rider::factory(),
            'company_id' => Company::factory(),
            'zone' => fake()->citySuffix(),
            'route' => 'Route '.fake()->numberBetween(1, 50),
            'shift' => fake()->randomElement(AssignmentShift::cases()),
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'status' => AssignmentStatus::Assigned,
        ];
    }
}
