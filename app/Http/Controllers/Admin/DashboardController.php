<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tính tổng doanh thu (Chỉ tính những đơn đã HOÀN THÀNH)
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');

        // 2. Đếm tổng số lượng các mục
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // 3. Đếm chi tiết trạng thái đơn hàng để Admin biết hôm nay phải làm gì
        $pendingOrders = Order::where('status', 'pending')->count();       // Chờ duyệt
        $processingOrders = Order::where('status', 'processing')->count(); // Đang xử lý
        $shippingOrders = Order::where('status', 'shipping')->count();     // Đang giao

        // 4. Lấy 5 đơn hàng mới nhất hiện ra bảng
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.index', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'totalCustomers',
            'pendingOrders', 'processingOrders', 'shippingOrders', 'recentOrders'
        ));
    }
}
