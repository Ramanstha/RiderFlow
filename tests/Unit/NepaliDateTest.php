<?php

namespace Tests\Unit;

use App\Support\NepaliDate;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NepaliDateTest extends TestCase
{
    /**
     * Known New Year's days (1 Baisakh) and other fixed points.
     */
    public static function knownDates(): array
    {
        return [
            'epoch' => ['1943-04-14', 2000, 1, 1],
            '2070 new year' => ['2013-04-14', 2070, 1, 1],
            '2081 new year' => ['2024-04-13', 2081, 1, 1],
            '2082 new year' => ['2025-04-14', 2082, 1, 1],
            '2083 new year' => ['2026-04-14', 2083, 1, 1],
            'mid-month' => ['2026-10-06', 2083, 6, 20],
            'last day of a year' => ['2026-04-13', 2082, 12, 30],
        ];
    }

    #[DataProvider('knownDates')]
    public function test_it_converts_ad_to_bs(string $ad, int $year, int $month, int $day): void
    {
        $this->assertSame(
            ['year' => $year, 'month' => $month, 'day' => $day],
            NepaliDate::fromAd(CarbonImmutable::parse($ad)),
        );
    }

    #[DataProvider('knownDates')]
    public function test_it_converts_bs_to_ad(string $ad, int $year, int $month, int $day): void
    {
        $this->assertSame($ad, NepaliDate::toAd($year, $month, $day)->toDateString());
    }

    public function test_it_round_trips_every_day_over_several_years(): void
    {
        for ($date = CarbonImmutable::parse('2020-01-01'); $date->year < 2030; $date = $date->addDay()) {
            $bs = NepaliDate::fromAd($date);

            $this->assertSame($date->toDateString(), NepaliDate::toAd($bs['year'], $bs['month'], $bs['day'])->toDateString());
        }
    }

    public function test_it_builds_month_keys_and_devanagari_digits(): void
    {
        $this->assertSame('2083-06', NepaliDate::monthKey(CarbonImmutable::parse('2026-10-06')));
        $this->assertSame('२०८३', NepaliDate::digits(2083));
    }

    public function test_it_formats_with_bs_date_tokens_and_ad_time_tokens(): void
    {
        $date = CarbonImmutable::parse('2026-10-06 14:05:00');

        $this->assertSame('2083-06-20', NepaliDate::format($date));
        $this->assertSame('Tuesday, 20 Ashwin 2083', NepaliDate::format($date, 'l, j F Y'));
        $this->assertSame('20 Ashwin 2083, 02:05 PM', NepaliDate::format($date, 'd M Y, h:i A'));
        $this->assertSame('2083 Y', NepaliDate::format($date, 'Y \\Y'));
    }

    public function test_it_finds_bs_month_boundaries(): void
    {
        $date = CarbonImmutable::parse('2026-10-06');

        $this->assertSame('2026-09-17', NepaliDate::startOfMonth($date)->toDateString());
        $this->assertSame('2026-10-17', NepaliDate::endOfMonth($date)->toDateString());
    }

    public function test_it_rejects_dates_outside_the_table(): void
    {
        $this->assertFalse(NepaliDate::isValid(2083, 13));
        $this->assertFalse(NepaliDate::isValid(2083, 6, 32));
        $this->assertFalse(NepaliDate::isValid(1999, 1));

        $this->expectException(\InvalidArgumentException::class);
        NepaliDate::fromAd(CarbonImmutable::parse('1900-01-01'));
    }
}
