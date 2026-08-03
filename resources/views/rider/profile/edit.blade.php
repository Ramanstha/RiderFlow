@extends('rider.layout.app')

@section('title', 'My Profile - Rider Portal')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">My Profile</h2>
        <p class="text-[var(--fg-secondary)]">Keep your contact details up to date</p>
    </div>

    <!-- Read-only account details -->
    <div class="card p-6 mb-6">
        <h3 class="font-display text-lg font-semibold mb-4">Account Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-[var(--fg-secondary)] mb-1">Business</p>
                <p class="font-medium">{{ $rider->company?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[var(--fg-secondary)] mb-1">License Number</p>
                <p class="font-medium">{{ $rider->license_number }}</p>
            </div>
            <div>
                <p class="text-[var(--fg-secondary)] mb-1">Status</p>
                <span class="badge badge-{{ $rider->status->value === 'Active' ? 'success' : ($rider->status->value === 'Inactive' ? 'danger' : 'warning') }}">
                    {{ $rider->status->value }}
                </span>
            </div>
        </div>
        <p class="text-xs text-[var(--fg-secondary)] mt-4">
            These are managed by your operations team. Contact them if something looks wrong.
        </p>
    </div>

    <!-- Editable profile -->
    <div class="card p-6 mb-6">
        <h3 class="font-display text-lg font-semibold mb-4">Contact Details</h3>
        <form action="{{ route('rider.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4">
                <img src="{{ $rider->photo ? asset('storage/'.$rider->photo) : 'https://ui-avatars.com/api/?name='.urlencode($rider->name) }}"
                    alt="{{ $rider->name }}" class="w-16 h-16 rounded-xl object-cover">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Photo</label>
                    <input type="file" name="photo" accept="image/*" class="input-field">
                    @error('photo') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $rider->name) }}" class="input-field" required>
                    @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $rider->phone) }}" class="input-field" required>
                    @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $rider->email) }}" class="input-field" required>
                @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-primary btn-sm">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Password -->
    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold mb-4">Change Password</h3>
        <form action="{{ route('rider.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Current Password *</label>
                <input type="password" name="current_password" class="input-field" placeholder="••••••••" required>
                @error('current_password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">New Password *</label>
                    <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                    @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••" required>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-primary btn-sm">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
