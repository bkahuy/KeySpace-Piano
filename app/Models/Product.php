<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model {
    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'price',
        'sale_price', 'stock_quantity', 'condition', 'color',
        'warranty_period', 'short_description', 'detailed_description', 'is_active'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function brand() {
        return $this->belongsTo(Brand::class);
    }
    public function images() {
        return $this->hasMany(ProductImage::class);
    }
    // Helper để lấy ảnh chính (primary image)
    public function primaryImage() {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
    public function reviews() {
        return $this->hasMany(Review::class)->where('is_approved', 1)->orderBy('id', 'desc');
    }
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
