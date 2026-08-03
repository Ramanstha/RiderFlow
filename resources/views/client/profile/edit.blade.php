@extends('client.layout.app')

@section('title', 'Company Profile - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Company Profile</h2>
    <p class="text-[var(--fg-secondary)] text-md">Your contact details and portal password.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h3 class="font-display text-lg font-bold mb-1">Contact Details</h3>
        <p class="text-xs text-[var(--fg-secondary)] mb-6">
            Your company name and account status are managed by RiderFlow. Contact your account manager to change them.
        </p>

        <form action="{{ route('client.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Company</label>
                <input type="text" class="input-field opacity-60" value="{{ $company->name }}" disabled>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Contact Person</label>
                <input type="text" name="contact_person" class="input-field"
                    value="{{ old('contact_person', $company->contact_person) }}" required>
                @error('contact_person') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Email Address</label>
                <input type="email" name="email" class="input-field" value="{{ old('email', $company->email) }}" required>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">This is the address you sign in with.</p>
                @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Phone</label>
                <input type="text" name="phone" class="input-field" value="{{ old('phone', $company->phone) }}" required>
                @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address</label>
                <input type="text" name="address" class="input-field" value="{{ old('address', $company->address) }}">
                @error('address') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary w-full text-center">Save Changes</button>
        </form>
    </div>

    <div class="card p-6">
        <h3 class="font-display text-lg font-bold mb-1">Change Password</h3>
        <p class="text-xs text-[var(--fg-secondary)] mb-6">
            @if ($company->last_login_at)
                Last signed in {{ $company->last_login_at->diffForHumans() }}.
            @else
                This is your first session.
            @endif
        </p>

        <form action="{{ route('client.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Current Password</label>
                <input type="password" name="current_password" class="input-field" placeholder="••••••••"
                    autocomplete="current-password" required>
                @error('current_password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">New Password</label>
                <input type="password" name="password" class="input-field" placeholder="••••••••"
                    autocomplete="new-password" required>
                @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••"
                    autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn-primary w-full text-center">Update Password</button>
        </form>
    </div>
</div>
@endsection
