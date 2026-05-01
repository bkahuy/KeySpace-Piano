<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Các danh mục gốc (Cha)
        $categories = [
            'Grand Piano',
            'Upright Piano',
            'Digital Piano (Piano Điện)',
            'Phụ kiện Piano'
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat, 'parent_id' => null]
            );
        }
    }
}
