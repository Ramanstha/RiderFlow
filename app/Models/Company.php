<?php

namespace App\Models;

use App\Enums\CompanyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /**
     * The company's roster: every rider it holds an assignment against. Riders
     * carry no company column, so rider_assignments is the pivot. Queries built
     * off this relation must qualify any column name the pivot also has
     * (id, timestamps) or the join makes it ambiguous.
     */
    public function riders(): BelongsToMany
    {
        return $this->belongsToMany(Rider::class, 'rider_assignments')
            ->distinct('riders.id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(RiderAssignment::class);
    }

    public function attendances(): HasManyThrough
    {
        return $this->throughAssignments(RiderAttendance::class, 'rider_attendances');
    }

    public function performances(): HasManyThrough
    {
        return $this->throughAssignments(RiderPerformance::class, 'rider_performances');
    }

    public function parcelCounts(): HasManyThrough
    {
        return $this->throughAssignments(DailyParcelCount::class, 'daily_parcel_counts');
    }

    /**
     * Reach a rider-owned table by hopping companies -> rider_assignments ->
     * <table>, deduped so a rider assigned twice does not double its rows.
     */
    protected function throughAssignments(string $related, string $table): HasManyThrough
    {
        return $this->hasManyThrough($related, RiderAssignment::class, 'company_id', 'rider_id', 'id', 'rider_id')
            ->distinct($table.'.id');
    }

    /**
     * Only active companies with an issued password may use the client portal.
     */
    public function canAccessPortal(): bool
    {
        return $this->password !== null && $this->status === CompanyStatus::Active;
    }
}
