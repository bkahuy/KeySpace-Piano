<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy ID của các thương hiệu và danh mục để gán cho sản phẩm
        $yamaha = Brand::where('name', 'Yamaha')->first()->id;
        $kawai = Brand::where('name', 'Kawai')->first()->id;
        $roland = Brand::where('name', 'Roland')->first()->id;

        $uprightId = Category::where('name', 'Upright Piano')->first()->id;
        $grandId = Category::where('name', 'Grand Piano')->first()->id;
        $digitalId = Category::where('name', 'Digital Piano (Piano Điện)')->first()->id;

        $products = [
            [
                'category_id' => $uprightId,
                'brand_id' => $yamaha,
                'name' => 'Yamaha U3H',
                'sku' => 'YAM-U3H-001',
                'price' => 55000000, // 55 triệu
                'sale_price' => 52000000,
                'stock_quantity' => 5,
                'condition' => 'used',
                'color' => 'Đen bóng',
                'warranty_period' => 60, // 5 năm
                'short_description' => 'Cây đàn Upright Piano quốc dân, âm thanh chuẩn mực.',
            ],
            [
                'category_id' => $grandId,
                'brand_id' => $kawai,
                'name' => 'Kawai GL-20',
                'sku' => 'KAW-GL20-001',
                'price' => 320000000, // 320 triệu
                'sale_price' => null,
                'stock_quantity' => 2,
                'condition' => 'new',
                'color' => 'Đen bóng',
                'warranty_period' => 120, // 10 năm
                'short_description' => 'Grand Piano Kawai GL-20 sở hữu bộ máy Millennium III độc quyền.',
            ],
            [
                'category_id' => $digitalId,
                'brand_id' => $roland,
                'name' => 'Roland RP-501R',
                'sku' => 'ROL-RP501-001',
                'price' => 28000000, // 28 triệu
                'sale_price' => 25500000,
                'stock_quantity' => 15,
                'condition' => 'new',
                'color' => 'Vân gỗ',
                'warranty_period' => 24, // 2 năm
                'short_description' => 'Piano điện Roland RP-501R thiết kế nhỏ gọn, âm thanh SuperNATURAL.',
            ]
        ];

        foreach ($products as $item) {
            $item['slug'] = Str::slug($item['name']);
            $item['detailed_description'] = "Mô tả chi tiết cho cây đàn " . $item['name'] . " sẽ được cập nhật tại đây...";
            $item['is_active'] = true;

            Product::updateOrCreate(
                ['sku' => $item['sku']],
                $item
            );
        }
    }
}
