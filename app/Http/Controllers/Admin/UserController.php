<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected UserService $users) {}

    public function index(Request $request): View
    {
        $this->authorize('users.manage');

        $users = $this->users->paginate($request->only(['search']));

        return view('admin.user.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('users.manage');

        $roles = Role::query()->orderBy('name')->pluck('name');

        return view('admin.user.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('users.manage');

        $this->users->create($request->validated());

        return redirect()->route('user.index')->with('success', 'User added successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('users.manage');

        $roles = Role::query()->orderBy('name')->pluck('name');

        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('users.manage');

        $this->users->update($user, $request->validated());

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('users.manage');

        $this->users->delete($user);

        return redirect()->route('user.index')->with('success', 'User removed successfully.');
    }
}
