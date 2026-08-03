<?php

namespace App\Repositories;

use App\Models\RiderAttendance;
use App\Repositories\Contracts\RiderAttendanceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class RiderAttendanceRepository extends BaseRepository implements RiderAttendanceRepositoryInterface
{
    public function __construct(RiderAttendance $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return $this->model->newQuery()->with('rider.company');
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['rider_id'] ?? null, fn (Builder $query, $riderId) => $query->where('rider_id', $riderId))
            ->when($filters['company_id'] ?? null, fn (Builder $query, $companyId) => $query->whereHas('rider', fn (Builder $q) => $q->where('company_id', $companyId)))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('date', '<=', $date));
    }
}
