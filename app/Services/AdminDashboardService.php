<?php

namespace App\Services;

use App\Models\DailyParcelCount;
use App\Models\RiderAttendance;
use App\Support\AttendanceSummary;
use App\Support\NepaliDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Read-side aggregates for the admin dashboard. Unlike the client and rider
 * portals these are fleet-wide: the admin sees every rider and every company.
 */
class AdminDashboardService
{
    public function dashboard(): array
    {
        return [
            'delivery' => $this->deliveryTrends(),
            'attendance' => $this->todayAttendance(),
        ];
    }

    /**
     * Today's rider status, counted the same way every other surface counts a
     * day (see {@see AttendanceSummary}). Riders with no row for today are not
     * counted either way — the nightly sweep is what closes the day's books.
     *
     * @return array{present: int, absent: int, total: int, rate: float}
     */
    public function todayAttendance(): array
    {
        $records = RiderAttendance::query()
            ->whereDate('date', CarbonImmutable::today()->toDateString())
            ->get();

        $present = AttendanceSummary::countPresent($records);
        $absent = AttendanceSummary::countAbsent($records);

        return [
            'present' => $present,
            'absent' => $absent,
            'total' => $present + $absent,
            'rate' => AttendanceSummary::rate($present, $present + $absent),
        ];
    }

    /**
     * Parcel figures behind the delivery chart, in both the periods the chart
     * can switch between.
     *
     * @return array{weekly: array{labels: list<string>, assigned: list<int>, delivered: list<int>}, monthly: array{labels: list<string>, assigned: list<int>, delivered: list<int>}}
     */
    public function deliveryTrends(): array
    {
        return [
            'weekly' => $this->weeklyTrend(),
            'monthly' => $this->monthlyTrend(),
        ];
    }

    /**
     * The current week, a point per day.
     *
     * @return array{labels: list<string>, assigned: list<int>, delivered: list<int>}
     */
    protected function weeklyTrend(): array
    {
        $start = CarbonImmutable::now()->startOfWeek();
        $end = $start->addDays(6);
        $totals = $this->parcelTotalsByDate($start, $end);

        $series = ['labels' => [], 'assigned' => [], 'delivered' => []];

        for ($day = $start; $day->lessThanOrEqualTo($end); $day = $day->addDay()) {
            $row = $totals->get($day->toDateString());

            $series['labels'][] = $day->format('D');
            $series['assigned'][] = (int) ($row->assigned ?? 0);
            $series['delivered'][] = (int) ($row->delivered ?? 0);
        }

        return $series;
    }

    /**
     * The current Nepali (BS) month in seven-day blocks — Week 1 is the 1st to
     * the 7th, so the buckets stay put as the month runs rather than shifting
     * with today.
     *
     * @return array{labels: list<string>, assigned: list<int>, delivered: list<int>}
     */
    protected function monthlyTrend(): array
    {
        $start = NepaliDate::startOfMonth(CarbonImmutable::today());
        $end = NepaliDate::endOfMonth($start);
        $totals = $this->parcelTotalsByDate($start, $end);

        $buckets = (int) ceil(($start->diffInDays($end) + 1) / 7);
        $series = [
            'labels' => [],
            'assigned' => array_fill(0, $buckets, 0),
            'delivered' => array_fill(0, $buckets, 0),
        ];

        for ($week = 1; $week <= $buckets; $week++) {
            $series['labels'][] = 'Week '.$week;
        }

        for ($day = $start; $day->lessThanOrEqualTo($end); $day = $day->addDay()) {
            $row = $totals->get($day->toDateString());

            if (! $row) {
                continue;
            }

            $bucket = intdiv((int) $start->diffInDays($day), 7);
            $series['assigned'][$bucket] += (int) $row->assigned;
            $series['delivered'][$bucket] += (int) $row->delivered;
        }

        return $series;
    }

    /**
     * Fleet-wide parcel totals for a date range, one row per date, keyed by
     * date so a day with no parcels simply has no entry.
     *
     * @return Collection<string, DailyParcelCount>
     */
    protected function parcelTotalsByDate(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return DailyParcelCount::query()
            ->whereDate('date', '>=', $from->toDateString())
            ->whereDate('date', '<=', $to->toDateString())
            ->groupBy('date')
            ->selectRaw('date')
            ->selectRaw('COALESCE(SUM(assigned), 0) as assigned')
            ->selectRaw('COALESCE(SUM(delivered), 0) as delivered')
            ->get()
            ->keyBy(fn (DailyParcelCount $row) => $row->date->toDateString());
    }
}
