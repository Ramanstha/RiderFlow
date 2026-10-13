@props([
    'name',
    'value' => null,
    'required' => false,
])

{{--
    A Bikram Sambat month field: year and month selects, with a hidden input
    submitting the AD date of the 1st of that BS month. `value` is any AD date
    inside the month.
--}}
@php
    $date = blank($value) ? \Carbon\CarbonImmutable::today() : \Carbon\CarbonImmutable::parse($value);
    $current = \App\Support\NepaliDate::fromAd($date);
    $start = \App\Support\NepaliDate::toAd($current['year'], $current['month']);
@endphp

<span class="flex gap-2" data-bs-month-picker>
    <select data-bs-year aria-label="Year (BS)" @required($required) {{ $attributes->merge(['class' => 'input-field']) }}>
        @foreach (range(\App\Support\NepaliDate::MIN_YEAR, \App\Support\NepaliDate::MAX_YEAR) as $year)
            <option value="{{ $year }}" @selected($year === $current['year'])>{{ $year }}</option>
        @endforeach
    </select>
    <select data-bs-month aria-label="Month (BS)" @required($required) {{ $attributes->merge(['class' => 'input-field']) }}>
        @foreach (\App\Support\NepaliDate::MONTHS as $number => $month)
            <option value="{{ $number }}" @selected($number === $current['month'])>{{ $month }}</option>
        @endforeach
    </select>
    <input type="hidden" name="{{ $name }}" value="{{ $start->toDateString() }}" data-bs-value>
</span>
