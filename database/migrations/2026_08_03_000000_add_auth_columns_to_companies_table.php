<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Companies are the "clients" of the fleet. These columns let a company sign
     * in to its own portal. Nullable so existing companies stay valid until an
     * admin issues credentials.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('password')->nullable()->after('contact_person');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token', 'last_login_at']);
        });
    }
};
