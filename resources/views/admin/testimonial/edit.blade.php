@extends('admin.layout.app')
@section('title', 'Edit Testimonial - RiderFlow')
@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Testimonial</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $testimonial->name }}</p>
    </div>

    <div class="card p-6">
        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}"
            class="w-24 h-24 object-cover rounded-full mb-6">

        <form action="{{ route('testimonial.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" class="input-field" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Designation *</label>
                <input type="text" name="designation" value="{{ old('designation', $testimonial->designation) }}" class="input-field" required>
                @error('designation') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Message *</label>
                <textarea name="message" rows="5" class="input-field" required>{{ old('message', $testimonial->message) }}</textarea>
                @error('message') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Replace Image</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field">
                <p class="text-xs text-[var(--fg-secondary)] mt-1">Leave empty to keep the current image. JPG, PNG or WebP. Max 2 MB.</p>
                @error('image') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('testimonial.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
