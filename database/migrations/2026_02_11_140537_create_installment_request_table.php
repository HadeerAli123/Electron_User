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
        Schema::create('installment_request', function (Blueprint $table) {
            $table->id();

    $table->foreignId('order_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('installment_plan_id')
        ->constrained('installment_plan')
        ->cascadeOnDelete();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->decimal('monthly_salary', 10, 2);

    $table->enum('status', ['pending', 'approved', 'rejected'])
        ->default('pending');

              $table->foreignId('referral_code_id')
                ->nullable()
                ->constrained('referral_codes')
                ->nullOnDelete();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_request');
    }
};
