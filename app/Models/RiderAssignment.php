<?php

namespace App\Models;

use App\Enums\AssignmentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'company_id',
        'type',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'type' => AssignmentType::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Assignments running on a given date (today by default). With no status
     * column, an assignment is active purely by its date range.
     */
    public function scopeActive(Builder $query, ?string $on = null): Builder
    {
        $on ??= now()->toDateString();

        return $query
            ->whereDate('start_date', '<=', $on)
            ->where(fn (Builder $query) => $query->whereNull('end_date')->orWhereDate('end_date', '>=', $on));
    }
}
