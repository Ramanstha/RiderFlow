<?php

namespace App\Enums;

enum AssignmentShift: string
{
    case Morning = 'Morning';
    case Afternoon = 'Afternoon';
    case Evening = 'Evening';
    case Night = 'Night';
}
