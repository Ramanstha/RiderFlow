{{-- Shared filter bar + export buttons for the client report pages. --}}
<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium mb-2">Rider</label>
            <select name="rider_id" class="input-field">
                <option value="">All riders</option>
                @foreach ($riders as $rider)
                    <option value="{{ $rider->id }}" @selected(request('rider_id') == $rider->id)>{{ $rider->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 text-center">Filter</button>
            <a href="{{ route($base) }}" class="btn-secondary text-center px-4">Reset</a>
        </div>
    </form>

    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-[var(--border)]">
        <span class="text-sm text-[var(--fg-secondary)]">Export as:</span>
        <a href="{{ route($base.'.csv', request()->query()) }}" class="btn-secondary text-sm">CSV</a>
        <a href="{{ route($base.'.xlsx', request()->query()) }}" class="btn-secondary text-sm">Excel</a>
        <a href="{{ route($base.'.pdf', request()->query()) }}" class="btn-secondary text-sm">PDF</a>
    </div>
</div>
