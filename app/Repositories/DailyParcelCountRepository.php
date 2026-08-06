<?php

namespace App\Repositories;

use App\Models\DailyParcelCount;
use App\Repositories\Contracts\DailyParcelCountRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class DailyParcelCountRepository extends BaseRepository implements DailyParcelCountRepositoryInterface
{
    public function __construct(DailyParcelCount $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return $this->model->newQuery()->with(['rider.companies', 'assignedByCompany:id,name']);
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['rider_id'] ?? null, fn (Builder $query, $riderId) => $query->where('rider_id', $riderId))
            ->when($filters['company_id'] ?? null, fn (Builder $query, $companyId) => $query->whereHas('rider.assignments', fn (Builder $q) => $q->where('company_id', $companyId)))
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('date', '<=', $date));
    }
}
