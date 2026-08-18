<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Attendance follows the parcel load now, so a day is Present or Absent and
     * nothing else — there are no shift times or notes to keep.
     */
    public function up(): void
    {
        Schema::table('rider_attendances', function (Blueprint $table) {
            $table->dropColumn(['check_in', 'check_out', 'remarks']);
        });
    }

    public function down(): void
    {
        Schema::table('rider_attendances', function (Blueprint $table) {
            $table->time('check_in')->nullable()->after('status');
            $table->time('check_out')->nullable()->after('check_in');
            $table->text('remarks')->nullable()->after('check_out');
        });
    }
};
