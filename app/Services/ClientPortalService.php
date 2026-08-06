<?php

namespace App\Services;

use App\Enums\RiderStatus;
use App\Models\Company;
use App\Models\DailyParcelCount;
use App\Models\RiderAttendance;
use App\Support\AttendanceSummary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Read-side aggregates for the client portal. Everything here is scoped to a
 * single company — a client never sees another company's fleet.
 */
class ClientPortalService
{
    public function dashboard(Company $company): array
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        return [
            'riders' => $this->riderCounts($company),
            'today' => $this->today($company),
            'month' => $this->parcelTotals($company, $monthStart, $monthEnd),
            'attendance' => $this->attendanceTotals($company, $monthStart, $monthEnd),
            'active_assignments' => $company->assignments()->active()->count(),
            'top_riders' => $this->topRiders($company, $monthStart, $monthEnd),
            'recent_activity' => $this->recentActivity($company),
        ];
    }

    /**
     * @return array{total: int, active: int, inactive: int}
     */
    public function riderCounts(Company $company): array
    {
        $byStatus = $company->riders()
            ->selectRaw('riders.status, COUNT(DISTINCT riders.id) as total')
            ->groupBy('riders.status')
            ->pluck('total', 'status');

        $active = (int) $byStatus->get(RiderStatus::Active->value, 0);

        return [
            'total' => (int) $byStatus->sum(),
            'active' => $active,
            'inactive' => (int) $byStatus->sum() - $active,
        ];
    }

    /**
     * @return array{present: int, absent: int, parcels: array<string, int|float>}
     */
    public function today(Company $company): array
    {
        $date = now()->toDateString();

        $attendance = $this->companyAttendance($company)
            ->whereDate('rider_attendances.date', $date)
            ->get();

        return [
            'present' => AttendanceSummary::countPresent($attendance),
            'absent' => AttendanceSummary::countAbsent($attendance),
            'parcels' => $this->parcelTotals($company, $date, $date),
        ];
    }

    /**
     * @return array{assigned: int, delivered: int, failed: int, returned: int, success_rate: float}
     */
    public function parcelTotals(Company $company, string $from, string $to): array
    {
        $totals = $this->companyParcels($company)
            ->whereDate('daily_parcel_counts.date', '>=', $from)
            ->whereDate('daily_parcel_counts.date', '<=', $to)
            ->selectRaw('COALESCE(SUM(assigned), 0) as assigned')
            ->selectRaw('COALESCE(SUM(delivered), 0) as delivered')
            ->selectRaw('COALESCE(SUM(failed), 0) as failed')
            ->selectRaw('COALESCE(SUM(`returned`), 0) as returned')
            ->first();

        $assigned = (int) ($totals->assigned ?? 0);
        $delivered = (int) ($totals->delivered ?? 0);

        return [
            'assigned' => $assigned,
            'delivered' => $delivered,
            'failed' => (int) ($totals->failed ?? 0),
            'returned' => (int) ($totals->returned ?? 0),
            'success_rate' => $assigned > 0 ? round(($delivered / $assigned) * 100, 2) : 0.0,
        ];
    }

    /**
     * @return array{total_days: int, present_days: int, absent_days: int, attendance_rate: float}
     */
    public function attendanceTotals(Company $company, string $from, string $to): array
    {
        return AttendanceSummary::forRecords(
            $this->companyAttendance($company)
                ->whereDate('rider_attendances.date', '>=', $from)
                ->whereDate('rider_attendances.date', '<=', $to)
                ->get()
        );
    }

    /**
     * Best performing riders this month, by parcels delivered.
     */
    public function topRiders(Company $company, string $from, string $to, int $limit = 5): Collection
    {
        return $this->companyParcels($company)
            ->whereDate('daily_parcel_counts.date', '>=', $from)
            ->whereDate('daily_parcel_counts.date', '<=', $to)
            ->join('riders as r', 'r.id', '=', 'daily_parcel_counts.rider_id')
            ->groupBy('r.id', 'r.name')
            ->selectRaw('r.id, r.name')
            ->selectRaw('COALESCE(SUM(assigned), 0) as assigned')
            ->selectRaw('COALESCE(SUM(delivered), 0) as delivered')
            ->orderByDesc('delivered')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'rider_id' => $row->id,
                'rider_name' => $row->name,
                'assigned' => (int) $row->assigned,
                'delivered' => (int) $row->delivered,
                'success_rate' => $row->assigned > 0 ? round(($row->delivered / $row->assigned) * 100, 2) : 0.0,
            ]);
    }

    /**
     * The company's most recent parcel submissions, newest first.
     */
    public function recentActivity(Company $company, int $limit = 10): Collection
    {
        return DailyParcelCount::query()
            ->with('rider:id,name')
            ->whereIn('rider_id', $company->riders()->select('riders.id'))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    protected function companyAttendance(Company $company): Builder
    {
        return RiderAttendance::query()
            ->whereIn('rider_id', $company->riders()->select('riders.id'));
    }

    protected function companyParcels(Company $company): Builder
    {
        return DailyParcelCount::query()
            ->whereIn('rider_id', $company->riders()->select('riders.id'));
    }
}
