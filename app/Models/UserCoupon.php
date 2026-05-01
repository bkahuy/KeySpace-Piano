<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    protected $fillable = [
        'user_id',
        'promotion_id',
        'code',
        'is_used',
        'used_at',
        'used_order_id',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

