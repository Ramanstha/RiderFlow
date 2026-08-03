<?php

namespace Tests\Concerns;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Laravel\Sanctum\Sanctum;

trait ActsWithRoles
{
    protected function actingAsSuperAdmin(): User
    {
        return $this->actingAsRole('Super Admin');
    }

    protected function actingAsManager(): User
    {
        return $this->actingAsRole('Manager');
    }

    protected function actingAsViewer(): User
    {
        return $this->actingAsRole('Viewer');
    }

    protected function actingAsRole(string $role): User
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole($role);

        Sanctum::actingAs($user);

        return $user;
    }
}
