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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('subtotal_amount')->default(0)->after('order_code');
            $table->unsignedBigInteger('discount_amount')->default(0)->after('total_amount');
            $table->foreignId('user_coupon_id')->nullable()->after('discount_amount')->constrained('user_coupons')->nullOnDelete();
            $table->unsignedInteger('used_reward_points')->default(0)->after('user_coupon_id');
            $table->unsignedBigInteger('points_discount_amount')->default(0)->after('used_reward_points');
            $table->unsignedInteger('earned_reward_points')->default(0)->after('points_discount_amount');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
