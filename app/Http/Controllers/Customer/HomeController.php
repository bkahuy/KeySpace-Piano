<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy danh sách danh mục gốc (để làm Menu hoặc hiển thị khối danh mục)
        $categories = Category::whereNull('parent_id')->get();

        // 2. Lấy 8 cây đàn MỚI NHẤT (có kèm ảnh đại diện và thương hiệu)
        $latestProducts = Product::with(['primaryImage', 'brand'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // 3. Lấy Chương trình GIẢM GIÁ
        $activePromotions = Promotion::query()
            ->where('is_active', true)
//            ->where(function ($query) {
//                $query->whereNull('starts_at')
//                    ->orWhere('starts_at', '<=', now());
//            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->when(Auth::check(), function ($query) {
                $query->with(['coupons' => function ($couponQuery) {
                    $couponQuery->where('user_id', Auth::id())
                        ->where('is_used', false);
                }]);
            })
            ->latest()
            ->take(6)
            ->get();


        // 4. (Tùy chọn) Lấy danh sách thương hiệu để hiển thị logo đối tác
        $brands = Brand::all();

        // Trả về view 'home' kèm theo các biến dữ liệu
        return view('customer.home', compact('categories', 'latestProducts', 'activePromotions', 'brands'));
    }
}
