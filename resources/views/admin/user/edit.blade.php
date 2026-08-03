@extends('admin.layout.app')

@section('title', 'Edit User - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit User</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $user->name }}'s account</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('user.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-field" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field" required>
                @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">New Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Leave blank to keep current">
                    @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="input-field">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Role *</label>
                <select name="role" class="input-field" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role', $user->roles->pluck('name')->first()) === $role)>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('user.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
