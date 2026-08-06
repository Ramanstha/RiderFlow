@extends('admin.layout.app')

@section('title', 'Add Rider - RiderFlow')

@section('content')
<div class="">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add New Rider</h2>
        <p class="text-[var(--fg-secondary)]">Create a new delivery rider profile</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('rider.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input-field"
                        placeholder="Enter full name" required>
                    @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                        placeholder="rider@example.com" required>
                    @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input-field"
                        placeholder="+1 (555) 000-0000" required>
                    @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">License Number *</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" class="input-field"
                        placeholder="e.g. MJ-2021-4567" required>
                    @error('license_number') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Status *</label>
                    <select name="status" class="input-field" required>
                        @foreach (\App\Enums\RiderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status') === $status->value)>{{ $status->value }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Photo</label>
                    <input type="file" name="photo" accept="image/*" class="input-field">
                    @error('photo') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-[var(--border)]">
                <p class="text-sm font-semibold mb-1">Rider Portal Credentials</p>
                <p class="text-xs text-[var(--fg-secondary)] mb-4">
                    Optional. Set a password to let this rider sign in at
                    <span class="font-mono">/rider-portal/login</span> — leave blank to issue it later.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Portal Password</label>
                        <input type="password" name="password" class="input-field" placeholder="••••••••" autocomplete="new-password">
                        @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('rider.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add Rider</button>
            </div>
        </form>
    </div>
</div>
@endsection