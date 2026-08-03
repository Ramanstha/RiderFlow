<?php

namespace App\Models;

use App\Enums\AssignmentShift;
use App\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'company_id',
        'zone',
        'route',
        'shift',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'shift' => AssignmentShift::class,
            'status' => AssignmentStatus::class,
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
}
