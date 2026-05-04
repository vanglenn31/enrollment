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
        Schema::table('student_enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('student_enrollments', 'units')) {
                $table->integer('units')->nullable()->after('course_id');
            }
        });
        Schema::table('terms', function (Blueprint $table) {
            if (!Schema::hasColumn('terms', 'status')) {
                $table->enum('status', ['active', 'ended'])->default('active')->after('semester');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropColumn('units');
        });
        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
