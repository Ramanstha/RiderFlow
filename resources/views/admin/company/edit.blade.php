@extends('admin.layout.app')

@section('title', 'Edit Company - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Company</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $company->name }}'s profile</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('company.update', $company) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Company Name *</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" class="input-field" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $company->email) }}" class="input-field">
                    @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $company->phone) }}" class="input-field">
                    @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $company->contact_person) }}" class="input-field">
                    @error('contact_person') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Status *</label>
                    <select name="status" class="input-field" required>
                        <option value="Active" @selected(old('status', $company->status->value) === 'Active')>Active</option>
                        <option value="Inactive" @selected(old('status', $company->status->value) === 'Inactive')>Inactive</option>
                    </select>
                    @error('status') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address</label>
                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="input-field">
                @error('address') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-[var(--border)]">
                <p class="text-sm font-semibold mb-1">Client Portal Credentials</p>
                <p class="text-xs text-[var(--fg-secondary)] mb-4">
                    @if ($company->password)
                        This company can sign in at <span class="font-mono">/login</span>.
                        @if ($company->last_login_at)
                            Last signed in {{ $company->last_login_at->diffForHumans() }}.
                        @else
                            They have not signed in yet.
                        @endif
                        Leave blank to keep their current password.
                    @else
                        No portal password has been issued yet. Set one to give this company access.
                    @endif
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            {{ $company->password ? 'Reset Password' : 'Portal Password' }}
                        </label>
                        <input type="password" name="password" class="input-field" placeholder="••••••••"
                            autocomplete="new-password">
                        @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••"
                            autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('company.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
