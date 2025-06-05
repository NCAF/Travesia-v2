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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->string('transaction_id')->unique()->comment('ID dari Midtrans');
            $table->string('payment_method', 100)->nullable();
            $table->decimal('gross_amount', 15, 2);
            $table->enum('status', ['pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'failure'])->default('pending');
            $table->string('payment_code')->nullable()->comment('untuk VA/QRIS');
            $table->string('fraud_status', 50)->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->json('midtrans_response')->nullable()->comment('menyimpan full response dari Midtrans');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['order_id', 'status']);
            $table->index('transaction_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
