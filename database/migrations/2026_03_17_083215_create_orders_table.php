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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // nullOnDelete để giữ lại lịch sử đơn hàng nếu user bị xóa
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_code')->unique();
            $table->unsignedBigInteger('total_amount');
            $table->enum('status', ['pending', 'processing', 'shipping', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cod', 'bank_transfer', 'vnpay', 'momo'])->default('cod');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_email');
            $table->text('order_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
