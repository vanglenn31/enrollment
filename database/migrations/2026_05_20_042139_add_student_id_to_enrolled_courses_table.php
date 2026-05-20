<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add student_id directly to enrolled_courses so academic records remain
     * queryable even after student_enrollment_id is nulled at term-end.
     */
    public function up(): void
    {
        Schema::table('enrolled_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->after('student_enrollment_id');
            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
        });

        // Back-fill student_id from the related StudentEnrollment rows
        DB::statement('
            UPDATE enrolled_courses ec
            JOIN student_enrollments se ON se.id = ec.student_enrollment_id
            SET ec.student_id = se.student_id
            WHERE ec.student_enrollment_id IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::table('enrolled_courses', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
    }
};