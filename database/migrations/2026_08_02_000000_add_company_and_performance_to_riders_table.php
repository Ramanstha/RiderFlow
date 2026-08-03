<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The application already reads riders.company_id / riders.performance, but no
     * migration ever created them. Add them here so a fresh install matches the code.
     */
    public function up(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            if (! Schema::hasColumn('riders', 'company_id')) {
                $table->foreignId('company_id')->after('status')->constrained('companies')->restrictOnDelete();
            }

            if (! Schema::hasColumn('riders', 'performance')) {
                $table->unsignedTinyInteger('performance')->nullable()->after('company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            if (Schema::hasColumn('riders', 'performance')) {
                $table->dropColumn('performance');
            }

            if (Schema::hasColumn('riders', 'company_id')) {
                $table->dropConstrainedForeignId('company_id');
            }
        });
    }
};
