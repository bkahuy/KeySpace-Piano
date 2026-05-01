<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. Hiển thị danh sách Đơn hàng
    public function index(Request $request)
    {
        // Bắt đầu query
        $query = Order::query()->orderBy('id', 'desc');

        // 1. Lọc theo từ khóa (Mã đơn hàng, Tên khách, hoặc Số điện thoại)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('order_code', 'like', "%{$keyword}%")
                    ->orWhere('shipping_name', 'like', "%{$keyword}%")
                    ->orWhere('shipping_phone', 'like', "%{$keyword}%");
            });
        }

        // 2. Lọc theo trạng thái đơn hàng
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Lọc theo phương thức thanh toán
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // 4. Lọc theo khoảng thời gian (Từ ngày -> Đến ngày)
        if ($request->has('created_at') && $request->created_at != '') {
            $query->whereDate('created_at', '=', $request->created_at);
        }

        // Thực thi query, phân trang và QUAN TRỌNG: giữ lại thông số lọc trên URL khi chuyển trang
        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /// 2. Hiển thị Chi tiết Đơn hàng
    public function show($id)
    {
        // Dùng with() để tải sẵn dữ liệu từ bảng order_items và products
        // Tránh lỗi N+1 Query làm chậm database
        $order = Order::with('orderItems.product')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    // 3. Xử lý Cập nhật Trạng thái Đơn hàng
    public function update(Request $request, $id)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,delivered,cancelled'
        ]);

        // Dùng with() lôi luôn chi tiết đơn ra để xử lý kho
        $order = Order::with(['orderItems', 'user', 'userCoupon'])->findOrFail($id);


        // Lưu lại trạng thái cũ để so sánh
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Nếu không có gì thay đổi thì quay về luôn
        if ($oldStatus === $newStatus) {
            return redirect()->back();
        }

        // 2. Bọc trong Transaction để an toàn dữ liệu
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Cập nhật trạng thái giao hàng
            $order->status = $newStatus;

            // LOGIC KẾT TOÁN: Tự động chốt thanh toán khi giao hàng thành công
            if ($newStatus === 'delivered') {
                $order->payment_status = 'paid';

                if ($order->user_id && $order->earned_reward_points == 0) {
                    // Gợi ý: mỗi 100.000đ được 1 điểm
                    $earnedPoints = floor($order->total_amount / 100000);

                    if ($earnedPoints > 0) {
                        $order->user->increment('reward_points', $earnedPoints);
                        $order->earned_reward_points = $earnedPoints;
                    }
                }
            }


            // (Nâng cao) Nếu Admin hủy đơn thì chuyển trạng thái thanh toán về chưa thanh toán
            if ($newStatus === 'cancelled') {
                $order->payment_status = 'unpaid';

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

                // Neu don da tung giao thanh cong va da cong diem, huy don thi tru lai diem da cong
                if ($order->earned_reward_points > 0 && $order->user) {
                    $pointsToSubtract = min($order->user->reward_points, $order->earned_reward_points);
                    $order->user->decrement('reward_points', $pointsToSubtract);
                    $order->earned_reward_points = 0;
                }
            }


            $order->save();

            // LOGIC "ĂN ĐIỂM": Xử lý Số lượng Tồn kho (Stock Quantity)

            // Trường hợp 1: Nếu đơn đang bình thường mà bị HỦY -> Phải cộng trả lại kho
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // Trường hợp 2: Nếu đơn đang bị Hủy, Admin lỡ tay bấm nhầm, giờ đổi lại thành Đang giao -> Phải trừ lại kho
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        // Kiểm tra xem kho còn đủ hàng để khôi phục đơn không
                        if ($product->stock_quantity >= $item->quantity) {
                            $product->decrement('stock_quantity', $item->quantity);
                        } else {
                            // Nếu kho không còn đủ, báo lỗi và rollback ngay
                            throw new \Exception('Sản phẩm "' . $product->name . '" không còn đủ số lượng trong kho để khôi phục đơn hàng này!');
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
