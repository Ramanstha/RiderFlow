{{--
    Monthly BS attendance calendar. Expects the monthlyCalendar() payload plus
    $route (the page's route name) and $routeParams (its route parameters), so
    month navigation stays on the page that included it.
--}}
@php
    $monthUrl = fn (?string $month = null) => route($route, array_merge($routeParams ?? [], $month ? ['month' => $month] : []));
@endphp
<!-- Calendar -->
<div class="card p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-2 min-w-0">
            @if ($previous)
            <a href="{{ $monthUrl($previous) }}"
                class="p-2 rounded-lg border border-[var(--border)] hover:border-[var(--accent)] transition-all"
                title="Previous month" rel="prev">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            @endif
            <div class="flex-1 min-w-0 sm:flex-none sm:min-w-[190px] text-center">
                <h3 class="font-display text-lg font-bold leading-tight">{{ $bs['label_ne'] }}</h3>
                <p class="text-xs text-[var(--fg-secondary)]">{{ $bs['label'] }} &middot; {{ $bs['ad_range'] }}</p>
            </div>
            @if ($next)
            <a href="{{ $monthUrl($next) }}"
                class="p-2 rounded-lg border border-[var(--border)] hover:border-[var(--accent)] transition-all"
                title="Next month" rel="next">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @endif
        </div>

        <form method="GET" action="{{ $monthUrl() }}" class="flex flex-wrap items-center gap-2"
            onchange="this.month.value = this.querySelector('[data-bs-year]').value + '-' + this.querySelector('[data-bs-month]').value.padStart(2, '0'); this.submit()">
            {{-- The selects are unnamed so only the combined BS month is submitted. --}}
            <input type="hidden" name="month" value="{{ $current }}">
            <select data-bs-year class="input-field w-auto flex-1 sm:flex-none text-sm" aria-label="Year (BS)">
                @foreach ($years as $year)
                <option value="{{ $year }}" @selected($year===$bs['year'])>{{ $year }}</option>
                @endforeach
            </select>
            <select data-bs-month class="input-field w-auto flex-1 sm:flex-none text-sm" aria-label="Month (BS)">
                @foreach (\App\Support\NepaliDate::MONTHS as $number => $name)
                <option value="{{ $number }}" @selected($number===$bs['month'])>{{ $name }}</option>
                @endforeach
            </select>
            <a href="{{ $monthUrl() }}" class="btn-secondary text-sm px-4">Today</a>
        </form>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap items-center gap-4 mb-5 text-xs text-[var(--fg-secondary)]">
        <span class="flex items-center gap-2"><span class="legend-dot legend-present"></span> Present</span>
        <span class="flex items-center gap-2"><span class="legend-dot legend-absent"></span> Absent</span>
    </div>

    <div class="calendar-grid mb-2">
        @foreach (\App\Support\NepaliDate::WEEKDAYS_NE as $index => $weekday)
        <div class="calendar-weekday"
            title="{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$index] }}">
            {{ $weekday }}</div>
        @endforeach
    </div>

    @foreach ($weeks as $week)
    <div class="calendar-grid">
        @foreach ($week as $cell)
        @if ($cell === null)
        <div class="calendar-cell calendar-cell-empty"></div>
        @else
        @php
        $status = $cell['status']?->value;
        $stateClass = match ($status) {
        'Present' => 'is-present',
        'Absent' => 'is-absent',
        default => '',
        };
        @endphp
        <div class="calendar-cell {{ $stateClass }} {{ $cell['date']->isToday() ? 'is-today' : '' }}"
            title="{{ \App\Support\NepaliDate::MONTHS[$bs['month']] }} {{ $cell['bs_day'] }}, {{ $bs['year'] }} ({{ $cell['date']->format('D, d M Y') }})@if ($status) — {{ $status }}@endif">
            <span class="flex items-baseline justify-between gap-1">
                <span class="calendar-date">{{ \App\Support\NepaliDate::digits($cell['bs_day']) }}</span>
                <span class="calendar-ad-date">{{ $cell['date']->format('M j') }}</span>
            </span>
            @if ($status)
            <span class="calendar-status">{{ $status }}</span>
            @endif
        </div>
        @endif
        @endforeach
    </div>
    @endforeach

    <p class="text-sm text-[var(--fg-secondary)] mt-5">
        {{ $summary['total_days'] }} {{ \Illuminate\Support\Str::plural('day', $summary['total_days']) }} tracked in {{ $bs['label'] }}
    </p>
</div>
