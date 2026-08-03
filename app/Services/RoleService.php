<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    protected const PROTECTED_ROLE = 'Super Admin';

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Role::query()
            ->withCount(['users', 'permissions'])
            ->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function permissions(): Collection
    {
        return Permission::query()->orderBy('name')->get();
    }

    public function create(array $data): Role
    {
        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        if ($role->name === self::PROTECTED_ROLE && $data['name'] !== self::PROTECTED_ROLE) {
            throw ValidationException::withMessages([
                'name' => 'The Super Admin role cannot be renamed.',
            ]);
        }

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }

    public function delete(Role $role): void
    {
        if ($role->name === self::PROTECTED_ROLE) {
            throw ValidationException::withMessages([
                'role' => 'The Super Admin role cannot be removed.',
            ]);
        }

        if ($role->users()->exists()) {
            throw ValidationException::withMessages([
                'role' => 'This role is assigned to users and cannot be removed.',
            ]);
        }

        $role->delete();
    }
}
