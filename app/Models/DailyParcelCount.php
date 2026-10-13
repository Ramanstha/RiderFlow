<?php

namespace App\Models;

use App\Observers\DailyParcelCountObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(DailyParcelCountObserver::class)]
class DailyParcelCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'assigned_by_company_id',
        'date',
        'assigned',
        'assigned_at',
        'delivered',
        'start_km',
        'end_km',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'assigned_at' => 'datetime',
        ];
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    /**
     * The client company that set the assigned figure, if a client set it.
     */
    public function assignedByCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'assigned_by_company_id');
    }

    /**
     * Did a client company assign this load? If so the rider reports outcomes
     * against it but may not change the assigned figure.
     */
    public function isClientAssigned(): bool
    {
        return $this->assigned_by_company_id !== null;
    }

    /**
     * Riders may only edit a count until its day has passed.
     */
    public function isEditableByRider(): bool
    {
        return $this->date->gte(today());
    }

    /**
     * Has the rider reported anything against this assignment yet?
     */
    public function hasRiderReport(): bool
    {
        return $this->delivered > 0;
    }

    /**
     * Parcels still to be accounted for by the rider.
     */
    public function getOutstandingAttribute(): int
    {
        return max(0, $this->assigned - ($this->delivered ?? 0));
    }

    /**
     * Distance covered for the day, from the odometer readings — null until
     * the end reading is in.
     */
    public function getKmRunAttribute(): ?int
    {
        if ($this->end_km === null) {
            return null;
        }

        return max(0, $this->end_km - $this->start_km);
    }

    public function getSuccessRateAttribute(): float
    {
        if ($this->assigned === 0) {
            return 0.0;
        }

        return round((($this->delivered ?? 0) / $this->assigned) * 100, 2);
    }
}
