<?php

namespace App\Console\Commands;

use App\Services\RiderAttendanceService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

/**
 * Attendance follows the parcels. A day that has a parcel row is marked as soon
 * as the row is touched (see DailyParcelCountObserver), but a day nobody was
 * assigned anything on never reaches the observer — this command closes those
 * days out, marking every active rider Absent.
 */
class MarkAttendanceFromParcels extends Command
{
    protected $signature = 'attendance:mark-from-parcels
                            {--date= : A single date (Y-m-d), defaults to today}
                            {--from= : First date of a range to backfill}
                            {--to= : Last date of the range, defaults to today}';

    protected $description = 'Mark active riders Present on the days they have parcels and Absent on the days they do not';

    public function __construct(protected RiderAttendanceService $attendances)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $range = $this->range();

        if (! $range) {
            return self::FAILURE;
        }

        [$from, $to] = $range;
        $rows = [];

        for ($day = $from; $day->lessThanOrEqualTo($to); $day = $day->addDay()) {
            $counts = $this->attendances->markDayFromParcels($day->toDateString());

            $rows[] = [$day->toDateString(), $counts['present'], $counts['absent']];
        }

        $this->table(['Date', 'Present', 'Absent'], $rows);
        $this->components->info(count($rows).' day(s) marked from parcel assignments.');

        return self::SUCCESS;
    }

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}|null
     */
    protected function range(): ?array
    {
        $today = CarbonImmutable::today();

        if ($date = $this->option('date')) {
            if ($this->option('from') || $this->option('to')) {
                $this->components->error('Use either --date or --from/--to, not both.');

                return null;
            }

            $day = $this->parse($date);

            return $day ? [$day, $day] : null;
        }

        if (! $this->option('from')) {
            return [$today, $today];
        }

        $from = $this->parse($this->option('from'));
        $to = $this->option('to') ? $this->parse($this->option('to')) : $today;

        if (! $from || ! $to) {
            return null;
        }

        if ($from->greaterThan($to)) {
            $this->components->error('--from is after --to.');

            return null;
        }

        return [$from, $to];
    }

    /**
     * A date that has not happened yet cannot be judged on parcels that have
     * not been assigned, so future dates are refused.
     */
    protected function parse(string $date): ?CarbonImmutable
    {
        try {
            $day = CarbonImmutable::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Throwable) {
            $this->components->error("[{$date}] is not a valid Y-m-d date.");

            return null;
        }

        if ($day->greaterThan(CarbonImmutable::today())) {
            $this->components->error("[{$date}] is in the future.");

            return null;
        }

        return $day;
    }
}
