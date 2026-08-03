<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    protected const ENTITIES = ['companies', 'riders', 'attendances', 'performances', 'assignments', 'parcels'];

    public function run(): void
    {
        $permissions = [];

        foreach (self::ENTITIES as $entity) {
            $permissions[] = "{$entity}.view";
            $permissions[] = "{$entity}.manage";
        }
        $permissions[] = 'reports.view';
        $permissions[] = 'users.manage';
        $permissions[] = 'site.manage';
        $permissions[] = 'admin.manage';

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $managerPermissions = array_filter($permissions, fn ($p) => ! str_starts_with($p, 'companies.') && $p !== 'users.manage');
        $viewerPermissions = array_filter($permissions, fn ($p) => str_ends_with($p, '.view'));

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions($managerPermissions);

        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions($viewerPermissions);
    }
}
