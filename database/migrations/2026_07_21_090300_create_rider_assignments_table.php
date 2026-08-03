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
        Schema::create('rider_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('riders')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->restrictOnDelete();
            $table->string('zone')->nullable();
            $table->string('route')->nullable();
            $table->enum('shift', ['Morning', 'Afternoon', 'Evening', 'Night'])->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['Assigned', 'Completed', 'Cancelled'])->default('Assigned');
            $table->timestamps();

            $table->index(['rider_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_assignments');
    }
};
