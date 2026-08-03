<?php

namespace App\Providers;

use App\Repositories\BannerRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ContactRepository;
use App\Repositories\Contracts\BannerRepositoryInterface;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Repositories\Contracts\DailyParcelCountRepositoryInterface;
use App\Repositories\Contracts\RiderAssignmentRepositoryInterface;
use App\Repositories\Contracts\RiderAttendanceRepositoryInterface;
use App\Repositories\Contracts\RiderPerformanceRepositoryInterface;
use App\Repositories\Contracts\RiderRepositoryInterface;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\DailyParcelCountRepository;
use App\Repositories\RiderAssignmentRepository;
use App\Repositories\RiderAttendanceRepository;
use App\Repositories\RiderPerformanceRepository;
use App\Repositories\RiderRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(RiderRepositoryInterface::class, RiderRepository::class);
        $this->app->bind(RiderAttendanceRepositoryInterface::class, RiderAttendanceRepository::class);
        $this->app->bind(RiderPerformanceRepositoryInterface::class, RiderPerformanceRepository::class);
        $this->app->bind(RiderAssignmentRepositoryInterface::class, RiderAssignmentRepository::class);
        $this->app->bind(DailyParcelCountRepositoryInterface::class, DailyParcelCountRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);
    }
}
