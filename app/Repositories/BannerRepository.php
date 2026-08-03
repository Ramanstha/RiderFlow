<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query
                ->where('title', 'like', "%{$search}%")
                ->orWhere('subtitle', 'like', "%{$search}%"))
            ->when($filters['is_active'] ?? null, fn (Builder $query) => $query
                ->where('is_active', true));
    }
}
