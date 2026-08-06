<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rider_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('riders')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['Present', 'Absent'])->default('Present');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['rider_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_attendances');
    }
};
