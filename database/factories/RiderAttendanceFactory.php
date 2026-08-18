<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Rider;
use App\Models\RiderAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiderAttendance>
 */
class RiderAttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rider_id' => Rider::factory(),
            'date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(AttendanceStatus::cases()),
        ];
    }
}
