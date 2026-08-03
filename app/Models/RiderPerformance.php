<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'period',
        'score',
        'deliveries_completed',
        'deliveries_failed',
        'attendance_rate',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period' => 'date',
            'attendance_rate' => 'decimal:2',
        ];
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }
}
