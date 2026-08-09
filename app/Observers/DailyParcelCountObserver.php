<?php

namespace App\Observers;

use App\Models\DailyParcelCount;
use App\Services\RiderAttendanceService;
use Illuminate\Support\Carbon;

/**
 * Attendance follows the parcels: give a rider a parcel load for a day and they
 * are marked Present for it; take it away and the day falls back to Absent.
 *
 * Hanging this off the model rather than the individual services means every
 * route in — client assignment, admin parcel CRUD, the rider portal, seeders —
 * keeps attendance consistent without each one remembering to.
 */
class DailyParcelCountObserver
{
    public function __construct(protected RiderAttendanceService $attendances) {}

    public function saved(DailyParcelCount $parcelCount): void
    {
        // An edit can move the row to another rider or another day, which
        // leaves the day it came from with one parcel load fewer.
        $previousRider = $parcelCount->getOriginal('rider_id');
        $previousDate = $parcelCount->getOriginal('date');
        $current = [$parcelCount->rider_id, $this->dateString($parcelCount->date)];

        if ($previousRider && $previousDate) {
            $previous = [(int) $previousRider, $this->dateString($previousDate)];

            if ($previous !== $current) {
                $this->attendances->syncFromParcels(...$previous);
            }
        }

        $this->attendances->syncFromParcels(...$current);
    }

    public function deleted(DailyParcelCount $parcelCount): void
    {
        $this->attendances->syncFromParcels($parcelCount->rider_id, $this->dateString($parcelCount->date));
    }

    /**
     * @param  Carbon|string  $date  Carbon off the model, or a raw string when
     *                               it comes back from an original attribute.
     */
    protected function dateString(Carbon|string $date): string
    {
        return $date instanceof Carbon ? $date->toDateString() : Carbon::parse($date)->toDateString();
    }
}
