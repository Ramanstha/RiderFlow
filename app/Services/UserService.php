<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(protected UserRepositoryInterface $users) {}

    public function list(array $filters = []): Collection
    {
        return $this->users->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->users->paginate($filters, $perPage);
    }

    public function find(int $id): User
    {
        return $this->users->findOrFail($id);
    }

    public function create(array $data): User
    {
        $role = $data['role'];
        unset($data['role']);

        $data['password'] = Hash::make($data['password']);

        $user = $this->users->create($data);
        $user->assignRole($role);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $role = $data['role'];
        unset($data['role']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->users->update($user, $data);
        $user->syncRoles([$role]);

        return $user;
    }

    public function delete(User $user): void
    {
        if (auth()->id() === $user->id) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        if ($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            throw ValidationException::withMessages([
                'user' => 'At least one Super Admin must remain.',
            ]);
        }

        $this->users->delete($user);
    }
}
