@extends('admin.layout.app')

@section('title', 'Add Testimonial - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add New Testimonial</h2>
        <p class="text-[var(--fg-secondary)]">Add a client testimonial for the website</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-field" required>
                @error('name') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Designation *</label>
                <input type="text" name="designation" value="{{ old('designation') }}" class="input-field" required>
                @error('designation') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Message *</label>
                <textarea name="message" rows="5" class="input-field" required>{{ old('message') }}</textarea>
                @error('message') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Image *</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field" required>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">JPG, PNG or WebP. Max 2 MB.</p>
                @error('image') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('testimonial.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add Testimonial</button>
            </div>
        </form>
    </div>
</div>
@endsection
