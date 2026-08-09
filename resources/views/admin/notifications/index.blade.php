@extends('admin.layout.app')

@section('title', 'Notifications - RiderFlow')

@section('content')
<div id="notificationsPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Notifications</h2>
            <p class="text-[var(--fg-secondary)] text-sm">
                {{ $unreadCount }} unread of {{ $notifications->total() }} total
            </p>
        </div>
        @if ($unreadCount > 0)
            <form action="{{ route('notification.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary text-sm px-5">Mark all as read</button>
            </form>
        @endif
    </div>

    @if (session('success'))
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="card overflow-hidden">
        <div class="divide-y divide-[var(--border)]">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = $notification->read_at === null;
                @endphp
                <div class="flex items-start gap-4 p-4 {{ $isUnread ? 'bg-[rgba(0,212,170,0.05)]' : '' }}">
                    <span class="mt-2 w-2 h-2 rounded-full shrink-0 {{ $isUnread ? 'bg-[var(--accent)]' : 'bg-[var(--border)]' }}"></span>

                    <div class="flex-1 min-w-0">
                        <p class="font-medium {{ $isUnread ? '' : 'text-[var(--fg-secondary)]' }}">
                            {{ $data['title'] ?? class_basename($notification->type) }}
                        </p>
                        @if (!empty($data['message']))
                            <p class="text-sm text-[var(--fg-secondary)] mt-1">{{ $data['message'] }}</p>
                        @endif
                        <p class="text-xs text-[var(--fg-secondary)] mt-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="flex items-center gap-1 shrink-0">
                        @if ($isUnread)
                            <form action="{{ route('notification.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all"
                                    title="Mark as read">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notification.destroy', $notification->id) }}" method="POST"
                            onsubmit="return confirm('Remove this notification?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-all"
                                title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-[var(--fg-secondary)] py-12">You have no notifications.</p>
            @endforelse
        </div>

        @if ($notifications->hasPages())
            <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
                <p class="text-sm text-[var(--fg-secondary)]">
                    Showing {{ $notifications->firstItem() ?? 0 }}-{{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} notifications
                </p>
                <div class="flex gap-2">
                    {{ $notifications->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
