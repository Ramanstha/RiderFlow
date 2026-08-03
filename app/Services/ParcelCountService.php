<?php

namespace App\Services;

use App\Models\DailyParcelCount;
use App\Repositories\Contracts\DailyParcelCountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ParcelCountService
{
    public function __construct(protected DailyParcelCountRepositoryInterface $parcelCounts) {}

    public function list(array $filters = []): Collection
    {
        return $this->parcelCounts->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->parcelCounts->paginate($filters, $perPage);
    }

    public function find(int $id): DailyParcelCount
    {
        return $this->parcelCounts->findOrFail($id);
    }

    public function create(array $data): DailyParcelCount
    {
        $this->guardDuplicateDate($data['rider_id'], $data['date']);

        return $this->parcelCounts->create($data);
    }

    public function update(DailyParcelCount $parcelCount, array $data): DailyParcelCount
    {
        $riderId = $data['rider_id'] ?? $parcelCount->rider_id;
        $date = $data['date'] ?? $parcelCount->date->toDateString();

        $this->guardDuplicateDate($riderId, $date, $parcelCount->id);

        return $this->parcelCounts->update($parcelCount, $data);
    }

    public function delete(DailyParcelCount $parcelCount): void
    {
        $this->parcelCounts->delete($parcelCount);
    }

    protected function guardDuplicateDate(int $riderId, string $date, ?int $ignoreId = null): void
    {
        $exists = DailyParcelCount::query()
            ->where('rider_id', $riderId)
            ->whereDate('date', $date)
            ->when($ignoreId, fn ($query, $ignoreId) => $query->whereKeyNot($ignoreId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'date' => 'A parcel count for this rider on this date has already been recorded.',
            ]);
        }
    }
}
