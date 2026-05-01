<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'code_prefix',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function coupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function isValidNow(): bool
    {
        return $this->is_active
            && (!$this->starts_at || now()->greaterThanOrEqualTo($this->starts_at))
            && (!$this->ends_at || now()->lessThanOrEqualTo($this->ends_at));
    }
}

