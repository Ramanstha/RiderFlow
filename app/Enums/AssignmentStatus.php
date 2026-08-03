<?php

namespace App\Enums;

enum AssignmentStatus: string
{
    case Assigned = 'Assigned';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
}
