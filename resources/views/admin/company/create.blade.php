@extends('admin.layout.app')

@section('title', 'Add Company - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add New Company</h2>
        <p class="text-[var(--fg-secondary)]">Create a new B2B client company</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('company.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Company Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-field"
                    placeholder="e.g. QuickDeliver Co." required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                        placeholder="contact@company.com">
                    @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input-field"
                        placeholder="+1 (555) 000-0000">
                    @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="input-field"
                        placeholder="e.g. Jane Doe">
                    @error('contact_person') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Status *</label>
                    <select name="status" class="input-field" required>
                        <option value="Active" @selected(old('status') === 'Active')>Active</option>
                        <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                    </select>
                    @error('status') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="input-field"
                    placeholder="Street, city, country">
                @error('address') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-[var(--border)]">
                <p class="text-sm font-semibold mb-1">Client Portal Credentials</p>
                <p class="text-xs text-[var(--fg-secondary)] mb-4">
                    Optional. Set a password to let this company sign in at <span class="font-mono">/login</span>
                    using the email address above — leave blank to issue it later.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Portal Password</label>
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

            <div class="flex gap-5 pt-4">
                <a href="{{ route('company.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add Company</button>
            </div>
        </form>
    </div>
</div>
@endsection
