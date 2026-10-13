<?php

namespace App\Models;

use App\Enums\RiderStatus;
use App\Support\NepaliDate;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Rider extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo',
        'pay',
        'license_number',
        'password',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'status' => RiderStatus::class,
            'pay' => 'decimal:2',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * A rider has no company column of its own — the companies it works for are
     * whichever ones hold an assignment against it.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'rider_assignments')
            ->distinct('companies.id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(RiderAttendance::class);
    }

    public function performances(): HasMany
    {
        return $this->hasMany(RiderPerformance::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(RiderAssignment::class);
    }

    public function parcelCounts(): HasMany
    {
        return $this->hasMany(DailyParcelCount::class);
    }

    public function salaryAdvances(): HasMany
    {
        return $this->hasMany(SalaryAdvance::class);
    }

    /**
     * Riders on the active roster; inactive ones stay out of pickers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', RiderStatus::Active);
    }

    /**
     * Riders free to take a new assignment: none of their assignments is still
     * running or scheduled to run on the given date (today by default). This
     * mirrors the overlap guard in RiderAssignmentService.
     */
    public function scopeUnassigned(Builder $query, ?string $on = null): Builder
    {
        $on ??= now()->toDateString();

        return $query->whereDoesntHave('assignments', fn (Builder $query) => $query
            ->where(fn (Builder $query) => $query->whereNull('end_date')->orWhereDate('end_date', '>=', $on)));
    }

    /**
     * `pay` is a per-day rate; a month's pay is that rate across every day of
     * the BS month the date falls in (the current month by default).
     */
    public function monthlyPay(?CarbonInterface $date = null): ?float
    {
        if ($this->pay === null) {
            return null;
        }

        $bs = NepaliDate::fromAd($date ?? now());

        return round((float) $this->pay * NepaliDate::daysInMonth($bs['year'], $bs['month']), 2);
    }

    /**
     * Only active riders with an issued password may use the portal.
     */
    public function canAccessPortal(): bool
    {
        return $this->password !== null && $this->status === RiderStatus::Active;
    }
}
