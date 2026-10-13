<?php

namespace App\Models;

use App\Enums\SalaryAdvanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryAdvance extends Model
{
    protected $fillable = [
        'rider_id',
        'amount',
        'reason',
        'needed_by',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $attributes = [
        'status' => 'Pending',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'needed_by' => 'date',
            'status' => SalaryAdvanceStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === SalaryAdvanceStatus::Pending;
    }
}
