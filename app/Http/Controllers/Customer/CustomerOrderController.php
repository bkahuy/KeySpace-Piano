<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    // 1. Hiển thị Lịch sử mua hàng
    public function index()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $availableCoupons = $user->coupons()
            ->with('promotion')
            ->where('is_used', false)
            ->latest()
            ->get()
            ->filter(function ($coupon) {
                return $coupon->promotion && $coupon->promotion->isValidNow();
            });

        return view('customer.orders.profile', compact('orders', 'availableCoupons'));
    }

    // 2. Hiển thị Chi tiết 1 đơn hàng của khách
    public function show($id)
    {
        // Phải check thêm điều kiện user_id để tránh việc khách này gõ URL xem trộm đơn của khách khác
        $order = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }
    // 3. Khách hàng tự Hủy đơn
    public function cancel($id)
    {
        // Tìm đơn hàng đúng của user đang đăng nhập
        $order = Order::with(['orderItems', 'user', 'userCoupon'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);


        // Bảo mật lớp 2: Chỉ cho phép hủy nếu đơn đang "Chờ duyệt"
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Bạn không thể hủy đơn hàng này vì nó đã được xử lý!');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Cập nhật trạng thái thành Đã hủy (Nhớ 2 chữ L nhé)
            $order->status = 'cancelled';
            // Hoan diem da dung
            if ($order->used_reward_points > 0 && $order->user) {
                $order->user->increment('reward_points', $order->used_reward_points);
                $order->used_reward_points = 0;
                $order->points_discount_amount = 0;
            }

            // Mo lai ma giam gia da dung
            if ($order->userCoupon) {
                $order->userCoupon->update([
                    'is_used' => false,
                    'used_at' => null,
                    'used_order_id' => null,
                ]);

                $order->user_coupon_id = null;
            }

            $order->save();

            // 2. LOGIC HOÀN KHO: Cộng trả lại số lượng đàn vào Database
            foreach ($order->orderItems as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', 'Bạn đã hủy đơn hàng thành công!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn, vui lòng thử lại!');
        }
    }
}
