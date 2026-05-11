<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrolled_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('professor_id')->nullable()->change();
            $table->unsignedBigInteger('room_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('enrolled_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('professor_id')->nullable(false)->change();
            $table->unsignedBigInteger('room_id')->nullable(false)->change();
        });
    }
};