<?php

namespace App\Repositories;

use App\Models\RiderAssignment;
use App\Repositories\Contracts\RiderAssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class RiderAssignmentRepository extends BaseRepository implements RiderAssignmentRepositoryInterface
{
    public function __construct(RiderAssignment $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return $this->model->newQuery()->with(['rider', 'company']);
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['rider_id'] ?? null, fn (Builder $query, $riderId) => $query->where('rider_id', $riderId))
            ->when($filters['company_id'] ?? null, fn (Builder $query, $companyId) => $query->where('company_id', $companyId))
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type));
    }
}
