@extends('admin.layout.app')

@section('title', 'Add Banner - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add New Banner</h2>
        <p class="text-[var(--fg-secondary)]">Upload a promotional banner video</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" class="input-field" required>
                @error('title') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Subtitle *</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="input-field" required>
                @error('subtitle') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Video *</label>
                <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime" class="input-field" required>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">MP4, WebM or MOV. Max 50 MB.</p>
                @error('video') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    Active
                </label>
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('banner.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Add Banner</button>
            </div>
        </form>
    </div>
</div>
@endsection
