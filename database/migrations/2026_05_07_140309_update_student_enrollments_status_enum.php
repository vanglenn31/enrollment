<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires re-declaring the full ENUM to change it.
        // Adjust the list below to match ALL current valid values in your column,
        // then add 'pending' to the set.
        DB::statement("
            ALTER TABLE student_enrollments
            MODIFY COLUMN status
            ENUM('pending','enrolled','verified','dropped','completed','cancelled')
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Revert: remove 'pending' — existing rows with status='pending' will
        // be truncated by MySQL, so only run this if no pending rows exist.
        DB::statement("
            ALTER TABLE student_enrollments
            MODIFY COLUMN status
            ENUM('enrolled','verified','dropped','completed','cancelled')
            NOT NULL DEFAULT 'enrolled'
        ");
    }
};