<?php

namespace App\Repositories;

use App\Models\Rider;
use App\Repositories\Contracts\RiderRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class RiderRepository extends BaseRepository implements RiderRepositoryInterface
{
    public function __construct(Rider $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return $this->model->newQuery()->with('companies');
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['company_id'] ?? null, fn (Builder $query, $companyId) => $query->whereHas('assignments', fn (Builder $q) => $q->where('company_id', $companyId)));
    }
}
