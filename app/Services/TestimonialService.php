<?php

namespace App\Services;

use App\Models\Testimonial;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TestimonialService
{
    public function __construct(protected TestimonialRepositoryInterface $testimonials) {}

    public function list(array $filters = []): Collection
    {
        return $this->testimonials->all($filters);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->testimonials->paginate($filters, $perPage);
    }

    public function find(int $id): Testimonial
    {
        return $this->testimonials->findOrFail($id);
    }

    public function create(array $data): Testimonial
    {
        $data['image'] = $this->storeImage($data['image']);

        return $this->testimonials->create($data);
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $previousImage = $testimonial->image;
        $replacement = null;

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $replacement = $data['image'] = $this->storeImage($data['image']);
        } else {
            unset($data['image']);
        }

        try {
            $testimonial = $this->testimonials->update($testimonial, $data);
        } catch (Throwable $e) {
            // Don't leave the upload behind if the record was never updated.
            if ($replacement) {
                Storage::disk('public')->delete($replacement);
            }

            throw $e;
        }

        if ($replacement && $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return $testimonial;
    }

    public function delete(Testimonial $testimonial): void
    {
        $image = $testimonial->image;

        // Drop the row first so a failed delete never orphans the record from its image.
        $this->testimonials->delete($testimonial);

        if ($image) {
            Storage::disk('public')->delete($image);
        }
    }

    protected function storeImage(UploadedFile $image): string
    {
        return $image->store('testimonials', 'public');
    }
}
