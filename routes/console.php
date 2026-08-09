<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Days with parcels are marked the moment the parcel row is touched; this
// closes out the rest of the fleet, who were assigned nothing and so are absent.
Schedule::command('attendance:mark-from-parcels')->dailyAt('23:50');
