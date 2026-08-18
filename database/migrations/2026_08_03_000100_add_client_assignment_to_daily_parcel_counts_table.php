<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A client company can assign a day's parcel load to one of its riders.
     * When it does, these columns record who assigned it and when — the rider
     * then reports delivered against that figure.
     *
     * Null means the rider logged the whole row themselves, which stays valid.
     */
    public function up(): void
    {
        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->foreignId('assigned_by_company_id')
                ->nullable()
                ->after('rider_id')
                ->constrained('companies')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->nullable()->after('assigned');
        });
    }

    public function down(): void
    {
        Schema::table('daily_parcel_counts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_by_company_id');
            $table->dropColumn('assigned_at');
        });
    }
};
