<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_suspended')->default(false);
            $table->boolean('is_withdrawn')->default(false);
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'is_suspended', 'is_withdrawn']);
        });
    }
};
