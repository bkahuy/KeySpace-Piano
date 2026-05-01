<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Ảnh chính
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'uploads/products/Roland_RP-501R_1.jpg', // Bạn có thể tạo folder này trong storage sau
                'is_primary' => true,
            ]);

            // Ảnh phụ góc nghiêng
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'uploads/products/Roland_RP-501R_2.jpg',
                'is_primary' => false,
            ]);
        }
    }
}
