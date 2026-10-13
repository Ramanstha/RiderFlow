@extends('admin.layout.app')

@section('title', 'Contact Messages - RiderFlow')

@section('content')
<div id="contactMessagesPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Contact Messages</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Enquiries submitted through the website contact form</p>
        </div>
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
        <form method="GET" action="{{ route('contact-message.index') }}" class="filter-bar flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name, email, phone or subject..." class="input-field flex-1 min-w-[200px]">
            <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td><p class="font-medium">{{ $message->name }}</p></td>
                            <td>
                                <a href="mailto:{{ $message->email }}" class="text-sm hover:underline">{{ $message->email }}</a>
                                @if ($message->phone)
                                    <p class="text-sm text-[var(--fg-secondary)]">{{ $message->phone }}</p>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm">{{ $message->subject ?: '—' }}</span>
                            </td>
                            <td class="whitespace-normal min-w-[16rem]">
                                <span class="text-sm text-[var(--fg-secondary)]">{{ Str::limit($message->message, 80) }}</span>
                            </td>
                            <td>
                                <span class="text-sm text-[var(--fg-secondary)]">{{ bs($message->created_at, 'd M Y, h:i A') }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('contact-message.show', $message) }}"
                                        class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('contact-message.destroy', $message) }}" method="POST"
                                        onsubmit="return confirm('Remove this message?');">
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
                            <td colspan="6" class="text-center text-[var(--fg-secondary)] py-8">No messages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 border-t border-[var(--border)]">
            <p class="text-sm text-[var(--fg-secondary)]">
                Showing {{ $messages->firstItem() ?? 0 }}-{{ $messages->lastItem() ?? 0 }} of {{ $messages->total() }} messages
            </p>
            <div class="flex gap-2">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
