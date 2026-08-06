<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    public function __construct(protected Model $model) {}

    public function all(array $filters = []): Collection
    {
        return $this->applyFilters($this->query(), $filters)->latest()->get();
    }

    /**
     * The newest record matching the filters, without hydrating the rest.
     */
    public function first(array $filters = []): ?Model
    {
        return $this->applyFilters($this->query(), $filters)->latest()->first();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters($this->query(), $filters)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model->refresh();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query;
    }
}
