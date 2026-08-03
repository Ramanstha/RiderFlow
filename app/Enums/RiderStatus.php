<?php

namespace App\Enums;

enum RiderStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
    case OnLeave = 'On Leave';
}
