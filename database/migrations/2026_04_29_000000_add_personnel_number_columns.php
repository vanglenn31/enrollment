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
        $this->addPersonnelNumberColumn('professors', 'professor_number');
        $this->addPersonnelNumberColumn('registrars', 'registrar_number');
    }

    protected function addPersonnelNumberColumn(string $tableName, string $columnName): void
    {
        if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, $columnName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName, $columnName) {
            $column = $table->string($columnName)->nullable();

            if (Schema::hasColumn($tableName, 'profile_id')) {
                $column->after('profile_id');
            } elseif (Schema::hasColumn($tableName, 'user_id')) {
                $column->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('professors') && Schema::hasColumn('professors', 'professor_number')) {
            Schema::table('professors', function (Blueprint $table) {
                $table->dropColumn('professor_number');
            });
        }

        if (Schema::hasTable('registrars') && Schema::hasColumn('registrars', 'registrar_number')) {
            Schema::table('registrars', function (Blueprint $table) {
                $table->dropColumn('registrar_number');
            });
        }
    }
};
