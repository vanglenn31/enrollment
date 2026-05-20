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
         DB::statement("
        DROP VIEW IF EXISTS programs_view
    ");

        DB::statement("
            CREATE VIEW programs_view AS
            SELECT 
                programs.id,
                programs.code,
                programs.name,
                programs.status,
                departments.name AS department_name
            FROM programs
            LEFT JOIN departments ON departments.id = programs.department_id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS programs_view");
    }
};
