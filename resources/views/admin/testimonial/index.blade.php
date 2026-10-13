@extends('admin.layout.app')

@section('title', 'Testimonials - RiderFlow')

@section('content')
<div id="testimonialsPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Testimonials</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Manage client testimonials shown on the website</p>
        </div>
        <a href="{{ route('testimonial.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add New Testimonial
        </a>
    </div>

    @if (session('success'))
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="card p-4 mb-6 border-l-4 border-l-red-500">
            <p class="text-sm text-red-400">{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="card p-3 mb-6">
        <form method="GET" action="{{ route('testimonial.index') }}" class="filter-bar flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search testimonials..."
                class="input-field flex-1 min-w-[200px]">
            <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}"
                                    class="w-12 h-12 object-cover rounded-full">
                            </td>
                            <td><p class="font-medium">{{ $testimonial->name }}</p></td>
                            <td>
                                <span class="text-sm">{{ $testimonial->designation }}</span>
                            </td>
                            <td class="whitespace-normal min-w-[16rem]">
                                <span class="text-sm text-[var(--fg-secondary)]">{{ Str::limit($testimonial->message, 80) }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('testimonial.edit', $testimonial) }}"
                                        class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('testimonial.destroy', $testimonial) }}" method="POST"
                                        onsubmit="return confirm('Remove this testimonial?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-all" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-[var(--fg-secondary)] py-8">No testimonials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 border-t border-[var(--border)]">
            <p class="text-sm text-[var(--fg-secondary)]">
                Showing {{ $testimonials->firstItem() ?? 0 }}-{{ $testimonials->lastItem() ?? 0 }} of {{ $testimonials->total() }} testimonials
            </p>
            <div class="flex gap-2">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
