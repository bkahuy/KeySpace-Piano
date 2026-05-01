<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Yamaha', 'Kawai', 'Roland', 'Casio', 'Steinway & Sons'];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand)],
                ['name' => $brand, 'description' => "Thương hiệu đàn piano cao cấp $brand"]
            );
        }
    }
}
