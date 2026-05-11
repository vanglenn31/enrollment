<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // The required downpayment amount (set per student or globally)
            $table->decimal('downpayment_amount', 10, 2)->default(0)->after('program');
            // Whether the student has cleared downpayment (set by admin after confirming payment)
            $table->boolean('downpayment_paid')->default(false)->after('downpayment_amount');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['downpayment_amount', 'downpayment_paid']);
        });
    }
};