<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImage extends Model {
    protected $fillable = ['product_id', 'image_path', 'is_primary'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    protected function getImagePathAttribute($value)
    {
        if (!$value) {
            return null;
        }
        // Kiểm tra nếu đường dẫn đã có 'storage/' ở đầu rồi thì không thêm nữa
        if (Str::startsWith($value, 'storage/')) {
            return $value;
        }
        return 'storage/' . $value;
    }

}
