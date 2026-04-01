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
        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('business_id')->constrained('businesses')->cascadeOnDelete(); 
            $table->decimal('amount', 15, 2);
            $table->integer('tenor'); 
            $table->string('purpose');
            $table->text('description')->nullable();
            $table->decimal('interest_rate', 5, 2); 
            $table->decimal('monthly_installment', 15, 2)->nullable();
            $table->decimal('total_interest', 15, 2)->nullable();
            $table->decimal('total_payment', 15, 2)->nullable();
            $table->string('status')->default('draft');
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};