<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    // Hiển thị form tra cứu
    public function index()
    {
        return view('customer.tracking.index');
    }

    // Xử lý tra cứu
    public function track(Request $request)
    {
        $request->validate([
            'order_code' => 'required|string',
            'email' => 'required|email',
        ], [
            'order_code.required' => 'Vui lòng nhập mã đơn hàng.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        $order = Order::with('user')->where('order_code', $request->order_code)->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng nào với mã này!')->withInput();
        }

        $orderEmail = $order->shipping_email ?? ($order->user->email ?? null);

        if (!$orderEmail || strtolower($orderEmail) !== strtolower($request->email)) {
            return back()->with('error', 'Email không khớp với thông tin đặt hàng!')->withInput();
        }

        // Thông báo thành công ngay sau khi xác thực email
        session()->flash('success', 'Tìm kiếm đơn hàng thành công');

        // ================= XỬ LÝ STATUS TẠI CONTROLLER =================
        $statusText = '';
        $statusBadge = '';

        switch ($order->status) {
            case 'pending':
                $statusText = 'Chờ duyệt đơn';
                $statusBadge = 'bg-warning text-dark';
                break;
            case 'processing':
                $statusText = 'Chờ xử lý';
                $statusBadge = 'bg-danger text-dark';
                break;
            case 'shipping':
                $statusText = 'Đang giao hàng';
                $statusBadge = 'bg-success text-dark';
                break;
            case 'delivered':
                $statusText = 'Hoàn thành';
                $statusBadge = 'bg-success';
                break;
            case 'cancelled':
                $statusText = 'Đã hủy';
                $statusBadge = 'bg-danger';
                break;
            default:
                $statusText = 'Không xác định';
                $statusBadge = 'bg-secondary';
                break;
        }

        // Trả dữ liệu đã xử lý sạch sẽ ra View
        return view('customer.tracking.index', compact('order', 'statusText', 'statusBadge'));
    }
}
