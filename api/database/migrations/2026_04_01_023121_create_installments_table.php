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
        Schema::create('amortization_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('loan_application_id')->constrained('loan_applications')->cascadeOnDelete();
            $table->integer('installment_number');
            $table->decimal('principal_payment', 15, 2);
            $table->decimal('interest_payment', 15, 2); 
            $table->decimal('total_payment', 15, 2);
            $table->decimal('remaining_balance', 15, 2);
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amortization_schedules');
    }
};