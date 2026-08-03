<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'failed',
        'returned',
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
     * Has the rider reported anything against this assignment yet?
     */
    public function hasRiderReport(): bool
    {
        return $this->delivered > 0 || $this->failed > 0 || $this->returned > 0;
    }

    /**
     * Parcels still to be accounted for by the rider.
     */
    public function getOutstandingAttribute(): int
    {
        return max(0, $this->assigned - $this->delivered - $this->failed - $this->returned);
    }

    public function getSuccessRateAttribute(): float
    {
        if ($this->assigned === 0) {
            return 0.0;
        }

        return round(($this->delivered / $this->assigned) * 100, 2);
    }
}
