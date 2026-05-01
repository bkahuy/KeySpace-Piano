<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::where('sku', 'YAM-U3H-001')->first();

        Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Đàn nguyên bản, âm thanh rất vang và sáng. Shop hỗ trợ vận chuyển lên lầu rất nhiệt tình. Chấm 10 điểm!',
            'is_approved' => true, // Đã duyệt cho hiện lên web
        ]);
    }
}
