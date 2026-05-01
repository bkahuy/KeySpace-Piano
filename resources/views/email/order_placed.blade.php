<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px;">
    <div style="text-align: center; border-bottom: 2px solid #primary; padding-bottom: 10px;">
        <h1 style="color: #0d6efd;">KeySpace Piano</h1>
        <p>Cảm ơn bạn đã tin tưởng lựa chọn chúng tôi!</p>
    </div>

    <div style="padding: 20px 0;">
        <h3>Xin chào {{ $order->shipping_name }},</h3>
        <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được hệ thống tiếp nhận thành công.</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Thông tin nhận hàng</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <p style="margin: 0;"><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                    <p style="margin: 0;"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    <p style="margin: 0;"><strong>Hình thức:</strong> {{ $order->payment_method == 'vnpay' ? 'Thanh toán VNPay' : 'COD' }}</p>
                </td>
            </tr>
            </tbody>
        </table>

        <p style="margin-top: 20px;">Chúng tôi sẽ sớm liên hệ để xác nhận và bàn giao nhạc cụ đến tận tay bạn.</p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('tracking.index', ['order_code' => $order->order_code, 'email' => $order->shipping_email]) }}"
               style="background-color: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Tra cứu tiến độ đơn hàng
            </a>
        </div>
    </div>

    <div style="margin-top: 20px; font-size: 12px; color: #777; text-align: center;">
        <p>Đây là email tự động, vui lòng không phản hồi email này.</p>
        <p>&copy; 2026 KeySpace Piano - Nhạc cụ dành cho tâm hồn.</p>
    </div>
</div>
