<?php

namespace App\Models;

use App\Enums\CompanyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A company is the client of the fleet: it books riders and, once an admin
 * issues credentials, signs in at /login to watch its own operation.
 */
class Company extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'contact_person',
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
            'status' => CompanyStatus::class,
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function riders(): HasMany
    {
        return $this->hasMany(Rider::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(RiderAssignment::class);
    }

    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(RiderAttendance::class, Rider::class);
    }

    public function performances(): HasManyThrough
    {
        return $this->hasManyThrough(RiderPerformance::class, Rider::class);
    }

    public function parcelCounts(): HasManyThrough
    {
        return $this->hasManyThrough(DailyParcelCount::class, Rider::class);
    }

    /**
     * Only active companies with an issued password may use the client portal.
     */
    public function canAccessPortal(): bool
    {
        return $this->password !== null && $this->status === CompanyStatus::Active;
    }
}
