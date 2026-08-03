@extends('admin.layout.app')

@section('title', 'Add Role - RiderFlow')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add New Role</h2>
        <p class="text-[var(--fg-secondary)]">Create a role and assign its permissions</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('role.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Role Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-field" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Permissions</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-4 rounded-xl border border-[var(--border)] max-h-96 overflow-y-auto">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                @checked(in_array($permission->name, old('permissions', [])))>
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>
                @error('permissions') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('role.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
