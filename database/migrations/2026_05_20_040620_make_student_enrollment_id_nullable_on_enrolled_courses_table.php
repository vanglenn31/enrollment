<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make student_enrollment_id nullable so that EnrolledCourse rows
     * (permanent academic records) survive when a StudentEnrollment is
     * deleted at term-end. The cascade constraint is also dropped so the
     * DB engine no longer auto-deletes enrolled_courses on enrollment delete.
     */
    public function up(): void
    {
        Schema::table('enrolled_courses', function (Blueprint $table) {
            // Drop the existing foreign key (Laravel's default naming convention)
            $table->dropForeign(['student_enrollment_id']);

            // Re-add as nullable with nullOnDelete instead of cascadeOnDelete
            $table->unsignedBigInteger('student_enrollment_id')
                  ->nullable()
                  ->change();

            $table->foreign('student_enrollment_id')
                  ->references('id')
                  ->on('student_enrollments')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enrolled_courses', function (Blueprint $table) {
            $table->dropForeign(['student_enrollment_id']);

            $table->unsignedBigInteger('student_enrollment_id')
                  ->nullable(false)
                  ->change();

            $table->foreign('student_enrollment_id')
                  ->references('id')
                  ->on('student_enrollments')
                  ->cascadeOnDelete();
        });
    }
};