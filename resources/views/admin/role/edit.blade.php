@extends('admin.layout.app')

@section('title', 'Edit Role - RiderFlow')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Role</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $role->name }}'s permissions</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('role.update', $role) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Role Name *</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="input-field"
                    {{ $role->name === 'Super Admin' ? 'readonly' : '' }} required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Permissions</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-4 rounded-xl border border-[var(--border)] max-h-96 overflow-y-auto">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                @checked(in_array($permission->name, old('permissions', $rolePermissions)))>
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>
                @error('permissions') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('role.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
