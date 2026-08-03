<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Credentials for the rider portal. Nullable password so riders created before
     * the portal existed simply cannot sign in until an admin issues one.
     */
    public function up(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->string('password')->nullable()->after('license_number');
            $table->timestamp('last_login_at')->nullable()->after('performance');
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->dropColumn(['password', 'last_login_at', 'remember_token']);
        });
    }
};
