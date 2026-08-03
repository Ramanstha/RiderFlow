@extends('admin.layout.app')

@section('title', 'Add Contact Details - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add Contact Details</h2>
        <p class="text-[var(--fg-secondary)]">Contact information shown on the website</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('contact-info.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                    placeholder="test@example.com" required>
                @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Phone *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input-field"
                    placeholder="+977-0000000000" required>
                @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address *</label>
                <textarea name="address" rows="3" class="input-field" required>{{ old('address') }}</textarea>
                @error('address') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Map Embed</label>
                <textarea name="map" rows="3" class="input-field"
                    placeholder="Google Maps embed iframe or URL">{{ old('map') }}</textarea>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">Optional. Paste the Google Maps embed code or link.
                </p>
                @error('map') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Facebook</label>
                <input type="url" name="facebook" value="{{ old('facebook') }}" class="input-field"
                    placeholder="https://facebook.com/...">
                @error('facebook') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Instagram</label>
                <input type="url" name="instagram" value="{{ old('instagram') }}" class="input-field"
                    placeholder="https://instagram.com/...">
                @error('instagram') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Tweeter</label>
                <input type="url" name="tweeter" value="{{ old('tweeter') }}" class="input-field"
                    placeholder="https://x.com/...">
                @error('tweeter') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('contact-info.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add Contact</button>
            </div>
        </form>
    </div>
</div>
@endsection