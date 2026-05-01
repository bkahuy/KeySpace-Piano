<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = [
        'user_id', 'order_code', 'total_amount', 'status', 'payment_method',
        'payment_status', 'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_email', 'order_notes','subtotal_amount',
        'discount_amount',
        'user_coupon_id',
        'used_reward_points',
        'points_discount_amount',
        'earned_reward_points',

    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function items() {
        return $this->hasMany(OrderItem::class);
    }
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
    public function userCoupon()
    {
        return $this->belongsTo(UserCoupon::class);
    }

}
