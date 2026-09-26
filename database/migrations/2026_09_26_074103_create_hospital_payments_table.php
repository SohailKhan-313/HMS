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
        Schema::create('hospital_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->string('category')->index(); // Consultation, Pharmacy / Medicine, General Procedures, Diagnostics / Lab, Emergency, Other
            $table->foreignId('patient_id')->nullable()->constrained('patienthistory')->nullOnDelete();
            $table->string('patient_name');
            $table->string('patient_phone')->nullable();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('Cash'); // Cash, Card, Bank Transfer, Wallet
            $table->date('payment_date');
            $table->string('status')->default('Paid'); // Paid, Partial, Pending, Refunded
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_payments');
    }
};
