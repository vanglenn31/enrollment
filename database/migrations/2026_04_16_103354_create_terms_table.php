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
        Schema::create('terms', function (Blueprint $table) {
            $table->id();

            // Basic term info
            $table->string('school_year');           // e.g. 2025-2026
            $table->enum('semester', ['1st', '2nd', 'summer']);

            // Term duration
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Status: upcoming | active | ended
            $table->enum('status', ['upcoming', 'active', 'ended'])->default('upcoming');

            // Enrollment window toggle (can open/close enrollment independently of term status)
            $table->boolean('is_enrollment_open')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};