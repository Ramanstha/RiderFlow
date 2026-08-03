<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Rider;
use Illuminate\Database\Seeder;

class RiderSeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function (Company $company) {
            Rider::factory()->count(3)->create(['company_id' => $company->id]);
        });

        // A known account for signing in to the rider portal.
        if ($company = Company::query()->first()) {
            Rider::factory()->create([
                'name' => 'Test Rider',
                'email' => 'rider@example.com',
                'company_id' => $company->id,
            ]);
        }
    }
}
