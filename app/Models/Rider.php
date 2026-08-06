<?php

namespace App\Models;

use App\Enums\RiderStatus;
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

    /**
     * Only active riders with an issued password may use the portal.
     */
    public function canAccessPortal(): bool
    {
        return $this->password !== null && $this->status === RiderStatus::Active;
    }
}
