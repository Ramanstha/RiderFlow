<?php

namespace App\Enums;

enum SalaryAdvanceStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public function badge(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
