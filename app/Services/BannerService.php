<?php

namespace App\Services;

use App\Models\Banner;
use App\Repositories\Contracts\BannerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BannerService
{
    public function __construct(protected BannerRepositoryInterface $banners) {}

    public function list(array $filters = []): Collection
    {
        return $this->banners->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->banners->paginate($filters, $perPage);
    }

    public function activeHeroBanner(): ?Banner
    {
        return $this->banners->first(['is_active' => true]);
    }

    public function find(int $id): Banner
    {
        return $this->banners->findOrFail($id);
    }

    public function create(array $data): Banner
    {
        $data['video'] = $this->storeVideo($data['video']);
        $data['is_active'] = $data['is_active'] ?? false;

        return $this->banners->create($data);
    }

    public function update(Banner $banner, array $data): Banner
    {
        $previousVideo = $banner->video;
        $replacement = null;

        if (isset($data['video']) && $data['video'] instanceof UploadedFile) {
            $replacement = $data['video'] = $this->storeVideo($data['video']);
        } else {
            unset($data['video']);
        }

        $data['is_active'] = $data['is_active'] ?? false;

        try {
            $banner = $this->banners->update($banner, $data);
        } catch (Throwable $e) {
            // Don't leave the upload behind if the record was never updated.
            if ($replacement) {
                Storage::disk('public')->delete($replacement);
            }

            throw $e;
        }

        if ($replacement && $previousVideo) {
            Storage::disk('public')->delete($previousVideo);
        }

        return $banner;
    }

    public function delete(Banner $banner): void
    {
        $video = $banner->video;

        // Drop the row first so a failed delete never orphans the record from its video.
        $this->banners->delete($banner);

        if ($video) {
            Storage::disk('public')->delete($video);
        }
    }

    protected function storeVideo(UploadedFile $video): string
    {
        return $video->store('banners', 'public');
    }
}
