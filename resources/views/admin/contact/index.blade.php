@extends('admin.layout.app')

@section('title', 'Contact Details - RiderFlow')

@section('content')
<div id="contactsPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Contact Details</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Manage the contact information shown on the website</p>
        </div>
        <a href="{{ route('contact-info.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Contact Details
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
        <form method="GET" action="{{ route('contact-info.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contact details..."
                class="input-field flex-1 min-w-[200px]">
            <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Social</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contacts as $contact)
                        <tr>
                            <td><p class="font-medium">{{ $contact->email }}</p></td>
                            <td><span class="text-sm">{{ $contact->phone }}</span></td>
                            <td><span class="text-sm">{{ Str::limit($contact->address, 60) }}</span></td>
                            <td>
                                <div class="flex items-center gap-2 text-sm">
                                    @forelse (collect([
                                        'Facebook' => $contact->facebook,
                                        'Instagram' => $contact->instagram,
                                        'Tweeter' => $contact->tweeter,
                                    ])->filter() as $label => $url)
                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                            class="badge badge-success">{{ $label }}</a>
                                    @empty
                                        <span class="text-[var(--fg-secondary)]">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('contact-info.edit', $contact) }}"
                                        class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('contact-info.destroy', $contact) }}" method="POST"
                                        onsubmit="return confirm('Remove these contact details?');">
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
                            <td colspan="5" class="text-center text-[var(--fg-secondary)] py-8">No contact details found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
            <p class="text-sm text-[var(--fg-secondary)]">
                Showing {{ $contacts->firstItem() ?? 0 }}-{{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} entries
            </p>
            <div class="flex gap-2">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
