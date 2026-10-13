@props([
    'name',
    'value' => null,
    'min' => null,
    'max' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'YYYY-MM-DD (BS)',
])

{{--
    A Bikram Sambat date field. The person sees and types the BS date; the
    hidden input submits the AD date (YYYY-MM-DD), so requests, validation and
    storage stay in AD. `value`, `min` and `max` are AD dates.
--}}
@php
    // Values often come straight from the query string, so junk reads as empty.
    $toAd = function ($date) {
        try {
            $ad = blank($date) ? '' : \Carbon\CarbonImmutable::parse($date)->toDateString();

            return $ad && \App\Support\NepaliDate::monthKey(\Carbon\CarbonImmutable::parse($ad)) ? $ad : '';
        } catch (\Throwable) {
            return '';
        }
    };
    $ad = $toAd($value);
@endphp

<span class="bs-date" data-bs-date data-min="{{ $toAd($min) }}" data-max="{{ $toAd($max) }}">
    <input type="text" data-bs-display value="{{ $ad ? bs($ad) : '' }}" placeholder="{{ $placeholder }}"
        autocomplete="off" inputmode="numeric" @required($required) @disabled($disabled)
        {{ $attributes->merge(['class' => 'input-field']) }}>
    <input type="hidden" name="{{ $name }}" value="{{ $ad }}" data-bs-value @disabled($disabled)>
</span>
