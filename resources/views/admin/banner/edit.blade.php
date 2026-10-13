@extends('admin.layout.app')
@section('title', 'Edit Banner - RiderFlow')
@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold mb-1">Edit Banner</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $banner->title }}</p>
    </div>

    <div class="card p-6">
        <video src="{{ asset('storage/' . $banner->video) }}" controls muted playsinline
            class="w-full max-h-48 object-cover rounded-xl mb-6"></video>

        <form action="{{ route('banner.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="input-field" required>
                @error('title') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Subtitle *</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="input-field" required>
                @error('subtitle') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Replace Video</label>
                <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="input-field">
                <p class="text-xs text-[var(--fg-secondary)] mt-1">Leave empty to keep the current video. MP4, WebM or MOV. Max 50 MB.</p>
                @error('video') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active))>
                    Active
                </label>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('banner.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
