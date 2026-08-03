@extends('admin.layout.app')

@section('title', 'Edit Rider - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Rider</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $rider->name }}'s profile</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('rider.update', $rider) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4">
                <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rider->name) }}"
                    alt="{{ $rider->name }}" class="avatar w-16 h-16">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Photo</label>
                    <input type="file" name="photo" accept="image/*" class="input-field">
                    @error('photo') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $rider->name) }}" class="input-field" placeholder="Enter full name" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $rider->email) }}" class="input-field" placeholder="rider@example.com" required>
                    @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $rider->phone) }}" class="input-field" placeholder="+1 (555) 000-0000" required>
                    @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">License Number *</label>
                <input type="text" name="license_number" value="{{ old('license_number', $rider->license_number) }}" class="input-field" placeholder="e.g. MJ-2021-4567" required>
                @error('license_number') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Status *</label>
                    <select name="status" class="input-field" required>
                        <option value="Active" @selected(old('status', $rider->status->value) === 'Active')>Active</option>
                        <option value="Inactive" @selected(old('status', $rider->status->value) === 'Inactive')>Inactive</option>
                        <option value="On Leave" @selected(old('status', $rider->status->value) === 'On Leave')>On Leave</option>
                    </select>
                    @error('status') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Performance Score (%)</label>
                    <input type="number" name="performance" value="{{ old('performance', $rider->performance) }}" class="input-field" min="0" max="100">
                    @error('performance') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Company *</label>
                <select name="company_id" class="input-field" required>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" @selected((string) old('company_id', $rider->company_id) === (string) $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-[var(--border)]">
                <p class="text-sm font-semibold mb-1">Rider Portal Credentials</p>
                <p class="text-xs text-[var(--fg-secondary)] mb-4">
                    @if ($rider->password)
                        This rider can sign in at <span class="font-mono">/rider-portal/login</span>.
                        Fill these in only to reset the password.
                        @if ($rider->last_login_at)
                            Last signed in {{ $rider->last_login_at->diffForHumans() }}.
                        @else
                            Has not signed in yet.
                        @endif
                    @else
                        No password issued yet — this rider cannot sign in to the portal.
                    @endif
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            {{ $rider->password ? 'New Portal Password' : 'Portal Password' }}
                        </label>
                        <input type="password" name="password" class="input-field" placeholder="••••••••" autocomplete="new-password">
                        @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('rider.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
