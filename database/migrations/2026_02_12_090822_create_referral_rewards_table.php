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
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            //صااحب الكود
               $table->foreignId('referrer_user_id')
                ->constrained('users')
                ->restrictOnDelete();
                  // الشخص اللي استخدم الكود
            $table->foreignId('referred_user_id')
                ->constrained('users')
                ->restrictOnDelete(); 

         $table->foreignId('installment_request_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_rewards');
    }
};
