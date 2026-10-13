<?php

use App\Support\NepaliDate;
use Carbon\CarbonImmutable;

if (! function_exists('bs')) {
    /**
     * An AD date as a Bikram Sambat (Nepali) date string, e.g.
     * bs($attendance->date, 'j F Y') → "20 Ashwin 2083". Null or empty gives ''.
     */
    function bs(DateTimeInterface|string|null $date, string $format = 'Y-m-d'): string
    {
        if ($date === null || $date === '') {
            return '';
        }

        return NepaliDate::format(CarbonImmutable::parse($date), $format);
    }
}
