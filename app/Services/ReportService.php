<?php

namespace App\Services;

use App\Models\Company;
use App\Models\DailyParcelCount;
use App\Models\Rider;
use App\Models\RiderAttendance;
use App\Models\RiderPerformance;
use App\Support\AttendanceSummary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ReportService
{
    public function attendanceSummary(array $filters = []): Collection
    {
        return RiderAttendance::query()
            ->with('rider.companies')
            ->when($filters['rider_id'] ?? null, fn (Builder $q, $id) => $q->where('rider_id', $id))
            ->when($filters['company_id'] ?? null, fn (Builder $q, $id) => $q->whereHas('rider.assignments', fn (Builder $r) => $r->where('company_id', $id)))
            ->when($filters['date_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '<=', $date))
            ->get()
            ->groupBy('rider_id')
            ->map(function ($records) {
                $rider = $records->first()->rider;

                return [
                    'rider_id' => $rider->id,
                    'rider_name' => $rider->name,
                    'company_name' => $this->companyNames($rider),
                    ...AttendanceSummary::forRecords($records),
                ];
            })
            ->values();
    }

    public function parcelSummary(array $filters = []): Collection
    {
        return DailyParcelCount::query()
            ->with('rider.companies')
            ->when($filters['rider_id'] ?? null, fn (Builder $q, $id) => $q->where('rider_id', $id))
            ->when($filters['company_id'] ?? null, fn (Builder $q, $id) => $q->whereHas('rider.assignments', fn (Builder $r) => $r->where('company_id', $id)))
            ->when($filters['date_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '<=', $date))
            ->get()
            ->groupBy('rider_id')
            ->map(function ($records) {
                $rider = $records->first()->rider;
                $assigned = $records->sum('assigned');
                $delivered = $records->sum('delivered');

                return [
                    'rider_id' => $rider->id,
                    'rider_name' => $rider->name,
                    'company_name' => $this->companyNames($rider),
                    'assigned' => $assigned,
                    'delivered' => $delivered,
                    'success_rate' => $assigned > 0 ? round(($delivered / $assigned) * 100, 2) : 0.0,
                ];
            })
            ->values();
    }

    public function performanceSummary(array $filters = []): Collection
    {
        return RiderPerformance::query()
            ->with('rider.companies')
            ->when($filters['rider_id'] ?? null, fn (Builder $q, $id) => $q->where('rider_id', $id))
            ->when($filters['company_id'] ?? null, fn (Builder $q, $id) => $q->whereHas('rider.assignments', fn (Builder $r) => $r->where('company_id', $id)))
            ->when($filters['date_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('period', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $q, $date) => $q->whereDate('period', '<=', $date))
            ->orderBy('period')
            ->get()
            ->groupBy('rider_id')
            ->map(function ($records) {
                $rider = $records->first()->rider;

                return [
                    'rider_id' => $rider->id,
                    'rider_name' => $rider->name,
                    'company_name' => $this->companyNames($rider),
                    'average_score' => round($records->avg('score'), 2),
                    'total_deliveries_completed' => $records->sum('deliveries_completed'),
                    'total_deliveries_failed' => $records->sum('deliveries_failed'),
                    'periods' => $records->map(fn ($r) => [
                        'period' => $r->period->format('Y-m'),
                        'score' => $r->score,
                    ])->values(),
                ];
            })
            ->values();
    }

    /**
     * A rider can be assigned to more than one company, so the report shows
     * every company it currently works for rather than a single owner.
     */
    protected function companyNames(Rider $rider): string
    {
        return $rider->companies->pluck('name')->join(', ') ?: '—';
    }

    public function companyOverview(Company $company, array $filters = []): array
    {
        $filters = array_merge($filters, ['company_id' => $company->id]);

        return [
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
            ],
            'attendance' => $this->attendanceSummary($filters),
            'parcels' => $this->parcelSummary($filters),
            'performance' => $this->performanceSummary($filters),
        ];
    }
}
