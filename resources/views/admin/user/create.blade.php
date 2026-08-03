@extends('admin.layout.app')

@section('title', 'Add User - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add New User</h2>
        <p class="text-[var(--fg-secondary)]">Create an admin panel account and assign a role</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-field" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-field" required>
                @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Password *</label>
                    <input type="password" name="password" class="input-field" required>
                    @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="input-field" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Role *</label>
                <select name="role" class="input-field" required>
                    <option value="">Select a role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('user.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add User</button>
            </div>
        </form>
    </div>
</div>
@endsection
