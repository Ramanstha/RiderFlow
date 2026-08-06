<?php

namespace App\Services;

use App\Models\RiderAssignment;
use App\Repositories\Contracts\RiderAssignmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class RiderAssignmentService
{
    public function __construct(protected RiderAssignmentRepositoryInterface $assignments) {}

    public function list(array $filters = []): Collection
    {
        return $this->assignments->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->assignments->paginate($filters, $perPage);
    }

    public function find(int $id): RiderAssignment
    {
        return $this->assignments->findOrFail($id);
    }

    public function create(array $data): RiderAssignment
    {
        $this->guardOverlap($data['rider_id'], $data['start_date'], $data['end_date'] ?? null);

        return $this->assignments->create($data);
    }

    public function update(RiderAssignment $assignment, array $data): RiderAssignment
    {
        $riderId = $data['rider_id'] ?? $assignment->rider_id;
        $startDate = $data['start_date'] ?? $assignment->start_date->toDateString();
        $endDate = $data['end_date'] ?? $assignment->end_date?->toDateString();

        $this->guardOverlap($riderId, $startDate, $endDate, $assignment->id);

        return $this->assignments->update($assignment, $data);
    }

    public function delete(RiderAssignment $assignment): void
    {
        $this->assignments->delete($assignment);
    }

    protected function guardOverlap(int $riderId, string $startDate, ?string $endDate, ?int $ignoreId = null): void
    {
        $overlaps = RiderAssignment::query()
            ->where('rider_id', $riderId)
            ->when($ignoreId, fn ($query, $ignoreId) => $query->whereKeyNot($ignoreId))
            ->where(function ($query) use ($startDate) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $startDate);
            })
            ->when($endDate, fn ($query, $endDate) => $query->where('start_date', '<=', $endDate))
            ->exists();

        if ($overlaps) {
            throw ValidationException::withMessages([
                'start_date' => 'This rider already has an assignment that overlaps this date range.',
            ]);
        }
    }
}
