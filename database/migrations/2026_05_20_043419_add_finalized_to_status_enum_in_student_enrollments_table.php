<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE student_enrollments
            MODIFY COLUMN status
            ENUM('pending','enrolled','verified','dropped','completed','cancelled','finalized')
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE student_enrollments
            MODIFY COLUMN status
            ENUM('pending','enrolled','verified','dropped','completed','cancelled')
            NOT NULL DEFAULT 'pending'
        ");
    }
};