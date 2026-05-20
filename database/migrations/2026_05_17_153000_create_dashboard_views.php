<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── View 1: v_enrollment_by_department ────────────────────────────
        //
        // Counts DISTINCT students who have at least one row in
        // student_enrollments, grouped by the department that owns
        // the course's program.
        //
        // Join path (verified against models):
        //   student_enrollments.course_id
        //     → courses.program_id
        //       → programs.department_id
        //         → departments.id
        //
        // StudentEnrollment  belongsTo  Course         (course_id)
        // Course             belongsTo  Program        (program_id)
        // Program            belongsTo  Department     (department_id)
        DB::statement("
            CREATE OR REPLACE VIEW v_enrollment_by_department AS
            SELECT
                d.id                              AS department_id,
                d.name                            AS department_name,
                COUNT(DISTINCT se.student_id)     AS enrolled_students
            FROM departments d
            INNER JOIN programs            p  ON p.department_id = d.id
            INNER JOIN courses             c  ON c.program_id    = p.id
            INNER JOIN student_enrollments se ON se.course_id    = c.id
            GROUP BY d.id, d.name
        ");

        // ── View 2: v_students_by_program ─────────────────────────────────
        //
        // Counts students per program.
        //
        // Join path (verified against models):
        //   students.program  →  programs.id
        //     Program hasMany Student via foreign key 'program'
        //     (non-standard FK name confirmed in Program model)
        //
        // Uses LEFT JOIN so programs with zero students still appear.
        DB::statement("
            CREATE OR REPLACE VIEW v_students_by_program AS
            SELECT
                p.id            AS program_id,
                p.name          AS program_name,
                p.code          AS program_code,
                d.name          AS department_name,
                COUNT(s.id)     AS student_count
            FROM programs p
            LEFT JOIN departments d ON d.id      = p.department_id
            LEFT JOIN students    s ON s.program = p.id
            GROUP BY p.id, p.name, p.code, d.name
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_enrollment_by_department');
        DB::statement('DROP VIEW IF EXISTS v_students_by_program');
    }
};