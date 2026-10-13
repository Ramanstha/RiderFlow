<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use InvalidArgumentException;

/**
 * Bikram Sambat (Nepali) calendar conversion.
 *
 * BS month lengths follow no formula — they are published year by year — so
 * conversion walks a lookup table from a fixed epoch: 1 Baisakh 2000 BS is
 * 14 April 1943 AD. The table covers 2000–2090 BS (1943–2034 AD).
 */
final class NepaliDate
{
    public const MIN_YEAR = 2000;

    public const MAX_YEAR = 2090;

    protected const EPOCH_AD = '1943-04-14';

    public const MONTHS = [
        1 => 'Baisakh', 'Jestha', 'Asar', 'Shrawan', 'Bhadra', 'Ashwin',
        'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra',
    ];

    public const MONTHS_NE = [
        1 => 'बैशाख', 'जेठ', 'असार', 'साउन', 'भदौ', 'असोज',
        'कात्तिक', 'मंसिर', 'पुस', 'माघ', 'फागुन', 'चैत',
    ];

    public const WEEKDAYS_NE = ['आइत', 'सोम', 'मंगल', 'बुध', 'बिहि', 'शुक्र', 'शनि'];

    /** Days in each month, Baisakh first. */
    protected const DAYS = [
        2000 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2001 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2002 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2003 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2004 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2005 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2006 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2007 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2008 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        2009 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2010 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2011 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2012 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        2013 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2014 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2015 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2016 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        2017 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2018 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2019 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2020 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        2021 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2022 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        2023 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2024 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        2025 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2026 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2027 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2028 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2029 => [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        2030 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2031 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2032 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2033 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2034 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2035 => [30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        2036 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2037 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2038 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2039 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        2040 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2041 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2042 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2043 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        2044 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2045 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2046 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2047 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        2048 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2049 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        2050 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2051 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        2052 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2053 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        2054 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2055 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2056 => [31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
        2057 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2058 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2059 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2060 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2061 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2062 => [30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
        2063 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2064 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2065 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2066 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
        2067 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2068 => [31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2069 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2070 => [31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
        2071 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2072 => [31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
        2073 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2074 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        2075 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2076 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        2077 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2078 => [31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
        2079 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2080 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
        2081 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2082 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2083 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2084 => [31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
        2085 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        2086 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        2087 => [31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30],
        2088 => [30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30],
        2089 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        2090 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
    ];

    /**
     * @return array{year: int, month: int, day: int}
     */
    public static function fromAd(CarbonInterface $date): array
    {
        $epoch = CarbonImmutable::parse(self::EPOCH_AD);
        $remaining = (int) $epoch->diffInDays($date->toImmutable()->startOfDay(), false);

        if ($remaining < 0) {
            throw new InvalidArgumentException('Date is before the supported Bikram Sambat range.');
        }

        foreach (self::DAYS as $year => $months) {
            $inYear = array_sum($months);

            if ($remaining >= $inYear) {
                $remaining -= $inYear;

                continue;
            }

            foreach ($months as $index => $days) {
                if ($remaining < $days) {
                    return ['year' => $year, 'month' => $index + 1, 'day' => $remaining + 1];
                }

                $remaining -= $days;
            }
        }

        throw new InvalidArgumentException('Date is after the supported Bikram Sambat range.');
    }

    /**
     * The BS `YYYY-MM` month an AD date falls in, e.g. 2026-10-06 → 2083-06.
     */
    public static function monthKey(CarbonInterface $date): string
    {
        $bs = self::fromAd($date);

        return sprintf('%04d-%02d', $bs['year'], $bs['month']);
    }

    public static function toAd(int $year, int $month, int $day = 1): CarbonImmutable
    {
        if (! self::isValid($year, $month, $day)) {
            throw new InvalidArgumentException("{$year}-{$month}-{$day} is not a supported Bikram Sambat date.");
        }

        $days = $day - 1;

        for ($y = self::MIN_YEAR; $y < $year; $y++) {
            $days += array_sum(self::DAYS[$y]);
        }

        for ($m = 1; $m < $month; $m++) {
            $days += self::DAYS[$year][$m - 1];
        }

        return CarbonImmutable::parse(self::EPOCH_AD)->addDays($days);
    }

    public static function daysInMonth(int $year, int $month): int
    {
        if (! isset(self::DAYS[$year][$month - 1])) {
            throw new InvalidArgumentException("{$year}-{$month} is not a supported Bikram Sambat month.");
        }

        return self::DAYS[$year][$month - 1];
    }

    public static function isValid(int $year, int $month, int $day = 1): bool
    {
        return isset(self::DAYS[$year][$month - 1])
            && $day >= 1
            && $day <= self::DAYS[$year][$month - 1];
    }

    /**
     * Format an AD date in BS, PHP-`date()` style.
     *
     * The date tokens are swapped for their BS values — Y, y (year); m, n
     * (month); d, j (day); F, M (month name); t (days in the month). Every
     * other letter (weekday, time of day, …) is the same in both calendars and
     * is taken from the AD date. A backslash escapes the next character.
     */
    public static function format(CarbonInterface $date, string $pattern = 'Y-m-d'): string
    {
        $bs = self::fromAd($date);
        $out = '';

        for ($i = 0, $length = strlen($pattern); $i < $length; $i++) {
            $char = $pattern[$i];

            if ($char === '\\') {
                $out .= $pattern[++$i] ?? '';

                continue;
            }

            $out .= match ($char) {
                'Y' => (string) $bs['year'],
                'y' => substr((string) $bs['year'], -2),
                'm' => sprintf('%02d', $bs['month']),
                'n' => (string) $bs['month'],
                'd' => sprintf('%02d', $bs['day']),
                'j' => (string) $bs['day'],
                'F', 'M' => self::MONTHS[$bs['month']],
                't' => (string) self::daysInMonth($bs['year'], $bs['month']),
                default => ctype_alpha($char) ? $date->format($char) : $char,
            };
        }

        return $out;
    }

    /**
     * The first day (AD) of the BS month the date falls in.
     */
    public static function startOfMonth(CarbonInterface $date): CarbonImmutable
    {
        $bs = self::fromAd($date);

        return self::toAd($bs['year'], $bs['month']);
    }

    /**
     * The last day (AD) of the BS month the date falls in.
     */
    public static function endOfMonth(CarbonInterface $date): CarbonImmutable
    {
        $bs = self::fromAd($date);

        return self::toAd($bs['year'], $bs['month'], self::daysInMonth($bs['year'], $bs['month']));
    }

    /**
     * Month lengths per BS year, for the browser-side date picker.
     *
     * @return array<int, array<int, int>>
     */
    public static function table(): array
    {
        return self::DAYS;
    }

    /**
     * Western digits as Devanagari: 2083 → २०८३.
     */
    public static function digits(int|string $value): string
    {
        return strtr((string) $value, ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९']);
    }
}
