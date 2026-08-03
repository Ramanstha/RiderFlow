<?php

namespace App\Services;

use App\Models\Rider;
use App\Repositories\Contracts\RiderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RiderService
{
    public function __construct(protected RiderRepositoryInterface $riders) {}

    public function list(array $filters = []): Collection
    {
        return $this->riders->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->riders->paginate($filters, $perPage);
    }

    public function find(int $id): Rider
    {
        return $this->riders->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $photo = null): Rider
    {
        $data = $this->withoutBlankPassword($data);

        if ($photo) {
            $data['photo'] = $photo->store('riders', 'public');
        }

        return $this->riders->create($data);
    }

    public function update(Rider $rider, array $data, ?UploadedFile $photo = null): Rider
    {
        $data = $this->withoutBlankPassword($data);

        $previousPhoto = $rider->photo;
        $replacement = null;

        if ($photo) {
            $replacement = $data['photo'] = $photo->store('riders', 'public');
        }

        try {
            $rider = $this->riders->update($rider, $data);
        } catch (Throwable $e) {
            // Don't leave the upload behind if the record was never updated.
            if ($replacement) {
                Storage::disk('public')->delete($replacement);
            }

            throw $e;
        }

        if ($replacement && $previousPhoto) {
            Storage::disk('public')->delete($previousPhoto);
        }

        return $rider;
    }

    public function delete(Rider $rider): void
    {
        $photo = $rider->photo;

        // Drop the row first so a failed delete never orphans the record from its photo.
        $this->riders->delete($rider);

        if ($photo) {
            Storage::disk('public')->delete($photo);
        }
    }

    /**
     * An empty password field means "leave the portal credentials alone",
     * never "wipe them".
     */
    protected function withoutBlankPassword(array $data): array
    {
        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
