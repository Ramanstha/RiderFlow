<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Rider;
use Illuminate\Database\Seeder;

class RiderSeeder extends Seeder
{
    public function run(): void
    {
        // A rider belongs to a company only through an assignment, so seed one
        // for each rider or the client portal has an empty roster.
        Company::all()->each(
            fn (Company $company) => Rider::factory()->count(3)->forCompany($company)->create()
        );

        // A known account for signing in to the rider portal.
        if ($company = Company::query()->first()) {
            Rider::factory()->forCompany($company)->create([
                'name' => 'Test Rider',
                'email' => 'rider@example.com',
            ]);
        }
    }
}
