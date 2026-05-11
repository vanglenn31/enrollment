<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
 
            // The pending payment record this request is settling
            $table->foreignId('payment_id')
                  ->constrained('payments')
                  ->cascadeOnDelete();
 
            // The student who submitted the request
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
 
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method');           // gcash, bank_transfer, cash, maya, other
            $table->string('reference_number')->nullable();
            $table->string('proof_of_payment');         // stored file path
            $table->text('note')->nullable();
 
            // pending | approved | rejected
            $table->string('status')->default('pending');
 
            // Admin who reviewed + their optional feedback
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
 
            $table->timestamps();
        });
 
        // Add for_review status + reference_number to payments table if not present
        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('payment_method');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
 
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
};
