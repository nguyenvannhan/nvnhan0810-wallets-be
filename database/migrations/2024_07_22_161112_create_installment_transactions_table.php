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
        Schema::create('installment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('wallet_account_id')->nullable();
            $table->unsignedBigInteger('monthly_amount');
            $table->date('start_paid_date');
            $table->date('next_paid_date');
            $table->unsignedInteger('remain_months');
            $table->unsignedInteger('total_months');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_transactions');
    }
};
