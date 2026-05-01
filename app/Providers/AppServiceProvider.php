<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Brand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Chia sẻ danh sách Danh mục và Thương hiệu cho TẤT CẢ các giao diện (để dùng cho Menu Header)
        // Dùng try-catch để phòng trường hợp bạn chưa chạy Migrate bảng categories
        try {
            View::share('globalCategories', Category::all());
            View::share('globalBrands', Brand::all());
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu database chưa sẵn sàng
        }
    }
}
