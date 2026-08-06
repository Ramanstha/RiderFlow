<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Rider;
use App\Models\Testimonial;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Services\TestimonialService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\ActsWithRoles;
use Tests\TestCase;

class MediaReplacementTest extends TestCase
{
    use ActsWithRoles, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_updating_a_testimonial_with_a_new_image_deletes_the_old_one(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->post(route('testimonial.store'), [
            'name' => 'Jane Doe',
            'designation' => 'Operations Lead',
            'message' => 'Great service.',
            'image' => UploadedFile::fake()->image('original.jpg'),
        ])->assertRedirect(route('testimonial.index'));

        $testimonial = Testimonial::firstOrFail();
        $oldPath = $testimonial->image;
        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($admin)->put(route('testimonial.update', $testimonial), [
            'name' => 'Jane Doe',
            'designation' => 'Operations Lead',
            'message' => 'Great service.',
            'image' => UploadedFile::fake()->image('replacement.jpg'),
        ])->assertRedirect(route('testimonial.index'));

        $newPath = $testimonial->fresh()->image;

        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_updating_a_testimonial_without_an_image_keeps_the_existing_one(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->post(route('testimonial.store'), [
            'name' => 'Jane Doe',
            'designation' => 'Operations Lead',
            'message' => 'Great service.',
            'image' => UploadedFile::fake()->image('original.jpg'),
        ]);

        $testimonial = Testimonial::firstOrFail();
        $oldPath = $testimonial->image;

        $this->actingAs($admin)->put(route('testimonial.update', $testimonial), [
            'name' => 'Jane Updated',
            'designation' => 'Operations Lead',
            'message' => 'Great service.',
        ])->assertRedirect(route('testimonial.index'));

        $testimonial->refresh();

        $this->assertSame($oldPath, $testimonial->image);
        $this->assertSame('Jane Updated', $testimonial->name);
        Storage::disk('public')->assertExists($oldPath);
    }

    public function test_deleting_a_testimonial_removes_its_image(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->post(route('testimonial.store'), [
            'name' => 'Jane Doe',
            'designation' => 'Operations Lead',
            'message' => 'Great service.',
            'image' => UploadedFile::fake()->image('original.jpg'),
        ]);

        $testimonial = Testimonial::firstOrFail();
        $path = $testimonial->image;

        $this->actingAs($admin)->delete(route('testimonial.destroy', $testimonial))
            ->assertRedirect(route('testimonial.index'));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_updating_a_banner_with_a_new_video_deletes_the_old_one(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->post(route('banner.store'), [
            'title' => 'Hero',
            'subtitle' => 'Fast delivery',
            'video' => UploadedFile::fake()->create('original.mp4', 100, 'video/mp4'),
            'is_active' => 1,
        ])->assertRedirect(route('banner.index'));

        $banner = Banner::firstOrFail();
        $oldPath = $banner->video;
        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($admin)->put(route('banner.update', $banner), [
            'title' => 'Hero',
            'subtitle' => 'Fast delivery',
            'video' => UploadedFile::fake()->create('replacement.mp4', 100, 'video/mp4'),
            'is_active' => 1,
        ])->assertRedirect(route('banner.index'));

        $newPath = $banner->fresh()->video;

        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_deleting_a_banner_removes_its_video(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $this->actingAs($admin)->post(route('banner.store'), [
            'title' => 'Hero',
            'subtitle' => 'Fast delivery',
            'video' => UploadedFile::fake()->create('original.mp4', 100, 'video/mp4'),
            'is_active' => 1,
        ]);

        $banner = Banner::firstOrFail();
        $path = $banner->video;

        $this->actingAs($admin)->delete(route('banner.destroy', $banner))
            ->assertRedirect(route('banner.index'));

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseCount('banners', 0);
    }

    public function test_updating_a_rider_with_a_new_photo_deletes_the_old_one(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create([
            'photo' => UploadedFile::fake()->image('original.jpg')->store('riders', 'public'),
        ]);

        $oldPath = $rider->photo;
        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($admin)->put(route('rider.update', $rider), [
            'name' => $rider->name,
            'email' => $rider->email,
            'phone' => $rider->phone,
            'license_number' => $rider->license_number,
            'status' => $rider->status->value,
            'photo' => UploadedFile::fake()->image('replacement.jpg'),
        ])->assertRedirect();

        $newPath = $rider->fresh()->photo;

        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_a_failed_update_keeps_the_old_image_and_discards_the_new_upload(): void
    {
        $testimonial = Testimonial::create([
            'name' => 'Jane Doe',
            'designation' => 'Operations Lead',
            'message' => 'Great service.',
            'image' => UploadedFile::fake()->image('original.jpg')->store('testimonials', 'public'),
        ]);

        $oldPath = $testimonial->image;

        $repository = $this->mock(TestimonialRepositoryInterface::class);
        $repository->shouldReceive('update')->once()->andThrow(new \RuntimeException('db down'));

        $service = new TestimonialService($repository);

        try {
            $service->update($testimonial, [
                'name' => 'Jane Doe',
                'designation' => 'Operations Lead',
                'message' => 'Great service.',
                'image' => UploadedFile::fake()->image('replacement.jpg'),
            ]);
            $this->fail('Expected the repository failure to bubble up.');
        } catch (\RuntimeException $e) {
            $this->assertSame('db down', $e->getMessage());
        }

        // The record still points at a file that exists, and no orphan was left behind.
        Storage::disk('public')->assertExists($oldPath);
        $this->assertCount(1, Storage::disk('public')->files('testimonials'));
    }

    public function test_deleting_a_rider_removes_its_photo(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $rider = Rider::factory()->create([
            'photo' => UploadedFile::fake()->image('original.jpg')->store('riders', 'public'),
        ]);

        $path = $rider->photo;

        $this->actingAs($admin)->delete(route('rider.destroy', $rider))->assertRedirect();

        Storage::disk('public')->assertMissing($path);
    }
}
