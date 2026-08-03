<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(protected RoleService $roles) {}

    public function index(Request $request): View
    {
        $this->authorize('users.manage');

        $roles = $this->roles->paginate($request->only(['search']));

        return view('admin.role.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('users.manage');

        $permissions = $this->roles->permissions();

        return view('admin.role.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->authorize('users.manage');

        $this->roles->create($request->validated());

        return redirect()->route('role.index')->with('success', 'Role added successfully.');
    }

    public function edit(Role $role): View
    {
        $this->authorize('users.manage');

        $permissions = $this->roles->permissions();
        $rolePermissions = $role->permissions->pluck('name')->all();

        return view('admin.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('users.manage');

        $this->roles->update($role, $request->validated());

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('users.manage');

        $this->roles->delete($role);

        return redirect()->route('role.index')->with('success', 'Role removed successfully.');
    }
}
