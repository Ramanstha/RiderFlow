@extends('admin.layout.app')

@section('title', 'Edit Contact Details - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold mb-1">Edit Contact Details</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $contact->email }}</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('contact-info.update', $contact) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $contact->email) }}" class="input-field" required>
                @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Phone *</label>
                <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}" class="input-field" required>
                @error('phone') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address *</label>
                <textarea name="address" rows="3" class="input-field" required>{{ old('address', $contact->address) }}</textarea>
                @error('address') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Map Embed</label>
                <textarea name="map" rows="3" class="input-field" placeholder="Google Maps embed iframe or URL">{{ old('map', $contact->map) }}</textarea>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">Optional. Paste the Google Maps embed code or link.</p>
                @error('map') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Facebook</label>
                <input type="url" name="facebook" value="{{ old('facebook', $contact->facebook) }}" class="input-field" placeholder="https://facebook.com/...">
                @error('facebook') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Instagram</label>
                <input type="url" name="instagram" value="{{ old('instagram', $contact->instagram) }}" class="input-field" placeholder="https://instagram.com/...">
                @error('instagram') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Tweeter</label>
                <input type="url" name="tweeter" value="{{ old('tweeter', $contact->tweeter) }}" class="input-field" placeholder="https://x.com/...">
                @error('tweeter') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('contact-info.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
