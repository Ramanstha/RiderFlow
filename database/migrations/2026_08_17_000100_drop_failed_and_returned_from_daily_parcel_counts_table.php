<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Parcel tracking now records assigned and delivered only.
     */
    public function up(): void
    {
        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->dropColumn(['failed', 'returned']);
        });
    }

    public function down(): void
    {
        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->unsignedInteger('failed')->default(0)->after('delivered');
            $table->unsignedInteger('returned')->default(0)->after('failed');
        });
    }
};
