<?php

namespace App\Enums;

enum AssignmentType: string
{
    case SubscriptionBasis = 'Subscription basis';
    case ParcelBasis = 'Parcel basis';
    case LastMile = 'Last mile';
    case Temporary = 'Temporary';
}
