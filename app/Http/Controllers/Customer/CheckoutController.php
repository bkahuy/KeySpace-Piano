<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use App\Models\UserCoupon;
use App\Models\Product;


class CheckoutController extends Controller
{
    // 1. Hiển thị trang Thanh toán
    public function index()
    {
        $cart = session()->get('cart', []);

        // Nếu giỏ hàng trống thì đuổi về trang chủ
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // ================= BẮT ĐẦU KIỂM TRA TỒN KHO =================
        // Duyệt qua từng sản phẩm trong giỏ hàng
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            // Trường hợp 1: Sản phẩm đã bị Admin xóa khỏi hệ thống
            if (!$product) {
                // Tùy chọn: Bạn có thể tự động unset($cart[$productId]) ở đây nếu muốn
                return redirect()->route('cart.index')->with('error', 'Một số sản phẩm trong giỏ hàng không còn tồn tại. Vui lòng kiểm tra lại!');
            }

            // Trường hợp 2: Số lượng khách muốn mua LỚN HƠN số lượng còn lại trong kho
            // (Bao gồm cả việc tồn kho = 0)
            if ($item['quantity'] > $product->stock_quantity && $product->stock_quantity > 0) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "' . $product->name . '" hiện chỉ còn ' . $product->stock_quantity . ' chiếc trong kho. Vui lòng giảm số lượng!');
            }

            if ($item['quantity'] > $product->stock_quantity && $product->stock_quantity == 0) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "' . $product->name . '" hiện đang tạm thời hết hàng. Vui lòng lựa chọn sản phẩm khác!');
            }

        }

        $availableCoupons = collect();

        if (Auth::check()) {
            $availableCoupons = Auth::user()->coupons()
                ->with('promotion')
                ->where('is_used', false)
                ->latest()
                ->get()
                ->filter(function ($coupon) {
                    return $coupon->promotion && $coupon->promotion->isValidNow();
                });
        }

        return view('customer.checkout.index', compact('cart', 'availableCoupons'));

    }

    // 2. Xử lý đặt hàng và lưu vào Database
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home');
        }

        // Validate dữ liệu Form gửi lên
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:10',
            'shipping_address' => 'required|string',
            'shipping_email' => 'required|string|max:255',
        ], [
            'shipping_name.required' => 'Vui lòng nhập họ tên người nhận.',
            'shipping_phone.required' => 'Vui lòng nhập số điện thoại.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'shipping_email.required' => 'Vui lòng nhập địa chỉ email.'
        ]);

        // Tính tổng tiền từ Session
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
        $subtotalAmount = $totalAmount;
        $couponDiscount = 0;
        $pointsDiscount = 0;
        $usedPoints = 0;
        $userCoupon = null;

        if (Auth::check() && $request->filled('coupon_code')) {
            $userCoupon = UserCoupon::with('promotion')
                ->where('user_id', Auth::id())
                ->where('code', $request->coupon_code)
                ->where('is_used', false)
                ->first();

            if (!$userCoupon || !$userCoupon->promotion->isValidNow()) {
                return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.')->withInput();
            }

            $promotion = $userCoupon->promotion;

            if ($subtotalAmount < $promotion->min_order_amount) {
                return back()->with('error', 'Đơn hàng chưa đạt giá trị tối thiểu để dùng mã.')->withInput();
            }

            if ($promotion->discount_type === 'percent') {
                $couponDiscount = floor($subtotalAmount * $promotion->discount_value / 100);

                if ($promotion->max_discount_amount) {
                    $couponDiscount = min($couponDiscount, $promotion->max_discount_amount);
                }
            } else {
                $couponDiscount = $promotion->discount_value;
            }

            $couponDiscount = min($couponDiscount, $subtotalAmount);
        }

        if (Auth::check() && $request->filled('used_reward_points')) {
            $user = Auth::user();

            $usedPoints = min((int) $request->used_reward_points, $user->reward_points);

            // 1 điểm = 1.000đ, tối đa giảm 30% tạm tính
            $pointValue = 10000;
            $maxPointsDiscount = floor($subtotalAmount * 0.3);

            $pointsDiscount = min($usedPoints * $pointValue, $maxPointsDiscount);
            $usedPoints = floor($pointsDiscount / $pointValue);
        }

        $totalAmount = max(0, $subtotalAmount - $couponDiscount - $pointsDiscount);


        // Dùng Database Transaction để đảm bảo tính toàn vẹn dữ liệu
        try {
            DB::beginTransaction();

            // Tạo mã đơn hàng ngẫu nhiên (VD: ORD-20260320-ABCD)
            $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // 1. Lưu vào bảng orders
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_code' => $orderCode,
                'subtotal_amount' => $subtotalAmount,
                'total_amount' => $totalAmount,
                'discount_amount' => $couponDiscount + $pointsDiscount,
                'user_coupon_id' => $userCoupon?->id,
                'used_reward_points' => $usedPoints,
                'points_discount_amount' => $pointsDiscount,
                'status' => 'pending',
                'payment_method' => $request->payment_method ?? 'cod',
                'payment_status' => 'unpaid',
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_email' => $request->shipping_email,
                'order_notes' => $request->order_notes,
            ]);


            // 2. Lưu vào bảng order_items
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'path_img' => $item['image'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // TRỪ SỐ LƯỢNG TỒN KHO TRONG BẢNG PRODUCTS
                // Dùng hàm decrement() của Laravel để trừ đi đúng số lượng khách mua
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            if ($userCoupon) {
                $userCoupon->update([
                    'is_used' => true,
                    'used_at' => now(),
                    'used_order_id' => $order->id,
                ]);
            }

            if (Auth::check() && $usedPoints > 0) {
                Auth::user()->decrement('reward_points', $usedPoints);
            }


            // Nếu mọi thứ OK, chốt lưu vào Database
            DB::commit();

            // Gửi mail thông báo
            try {
                Mail::to($order->shipping_email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                // Nếu lỗi gửi mail (do cấu hình sai) thì ghi log,
                // không nên để web bị văng lỗi 500 làm khách hàng sợ
                \Log::error('Lỗi gửi mail đơn hàng: ' . $e->getMessage());
            }

            // Xóa Session giỏ hàng
            session()->forget('cart');

            // Chuyển hướng sang trang Thành công kèm mã đơn hàng
            return redirect()->route('checkout.success')->with('orderCode', $orderCode);

        } catch (\Exception $e) {
            // Nếu có lỗi (VD: sập DB), hoàn tác mọi thứ
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình đặt hàng. Vui lòng thử lại!')->withInput();
        }
    }

    // 3. Hiển thị thông báo thành công
    public function success()
    {
        // Chặn người dùng gõ link thẳng vào trang này nếu không có orderCode
        if (!session('orderCode')) {
            return redirect()->route('home');
        }
        return view('customer.checkout.success');
    }

    // 4. Nhận kết quả trả về từ VNPay
    public function vnpayReturn(Request $request)
    {
        // 1. Lấy mã băm bảo mật do VNPay gửi về
        $vnp_SecureHash = $request->vnp_SecureHash;

        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        // Bỏ cái hash do VNPay gửi ra để tự tính toán lại
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Lấy Secret Key từ .env để đối chiếu
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // 2. KIỂM TRA BẢO MẬT: So sánh chữ ký tự tính với chữ ký VNPay gửi về
        if ($secureHash == $vnp_SecureHash) {

            // Nếu mã phản hồi là '00' nghĩa là THANH TOÁN THÀNH CÔNG
            if ($request->vnp_ResponseCode != '00') {

                // Lấy mã đơn hàng từ VNPay trả về
                $orderCode = $request->vnp_TxnRef;

                // Tìm đơn hàng trong Database
                $order = Order::where('order_code', $orderCode)->first();

                if ($order) {
                    // CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG THÀNH ĐÃ THANH TOÁN
                    $order->payment_status = 'paid'; // Đã thanh toán
                    $order->status = 'processing';
                    $order->save();

                    // Chuyển hướng sang trang Thành công
                    return redirect()->route('checkout.success')->with('orderCode', $order->order_code);
                }
            } else {
                // Nếu khách hủy thanh toán hoặc tài khoản hết tiền
                return redirect()->route('home')->with('error', 'Giao dịch thanh toán đã bị hủy hoặc không thành công.');
            }
        } else {
            // Lỗi bảo mật: Dữ liệu bị giả mạo trên đường truyền
            return redirect()->route('home')->with('error', 'Lỗi bảo mật: Chữ ký không hợp lệ!');
        }
    }
}
