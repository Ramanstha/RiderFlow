@extends('admin.layout.app')

@section('title', 'Contact Message - RiderFlow')

@section('content')
<div id="contactMessageShowPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1 break-anywhere">{{ $message->subject ?: 'Contact Message' }}</h2>
            <p class="text-[var(--fg-secondary)] text-sm">
                Received {{ bs($message->created_at, 'd M Y, h:i A') }}
            </p>
        </div>
        <a href="{{ route('contact-message.index') }}" class="btn-secondary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Messages
        </a>
    </div>

    <div class="card p-6 space-y-6">
        <div class="grid gap-6 sm:grid-cols-3">
            <div>
                <p class="text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider mb-1">Name</p>
                <p class="font-medium">{{ $message->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider mb-1">Email</p>
                <a href="mailto:{{ $message->email }}" class="font-medium hover:underline break-anywhere">{{ $message->email }}</a>
            </div>
            <div>
                <p class="text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider mb-1">Phone</p>
                <p class="font-medium">{{ $message->phone ?: '—' }}</p>
            </div>
        </div>

        <div>
            <p class="text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider mb-2">Message</p>
            <p class="text-sm leading-relaxed whitespace-pre-line break-anywhere">{{ $message->message }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-[var(--border)]">
            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?: 'Your enquiry')) }}"
                class="btn-primary text-sm px-5">Reply by Email</a>
            <form action="{{ route('contact-message.destroy', $message) }}" method="POST"
                onsubmit="return confirm('Remove this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-secondary text-sm px-5 text-red-400">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
