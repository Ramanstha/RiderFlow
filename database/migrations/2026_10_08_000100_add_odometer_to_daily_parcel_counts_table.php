<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Odometer readings at the start and end of the rider's day; the distance
     * run is derived from them rather than stored.
     *
     * A row can be opened at the start of the day, before the end reading and
     * the delivered figure are known, so both of those may be null.
     */
    public function up(): void
    {
        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->unsignedInteger('delivered')->nullable()->default(null)->change();
            $table->unsignedInteger('start_km')->default(0)->after('delivered');
            $table->unsignedInteger('end_km')->nullable()->after('start_km');
        });
    }

    public function down(): void
    {
        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->dropColumn(['start_km', 'end_km']);
        });

        DB::table('daily_parcel_counts')->whereNull('delivered')->update(['delivered' => 0]);

        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->unsignedInteger('delivered')->default(0)->change();
        });
    }
};
