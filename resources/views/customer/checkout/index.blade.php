@extends('layout.index')
@section('title', 'Thanh toán đơn hàng')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Thanh toán đơn hàng</h2>

        {{-- Hiển thị thông báo lỗi Validate nếu có --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                {{-- CỘT TRÁI: Form điền thông tin --}}
                <div class="col-md-7">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Thông tin giao hàng</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_name" class="form-control" value="{{ Auth::check() ? Auth::user()->name : old('shipping_name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_phone" class="form-control" value="{{ Auth::check() ? Auth::user()->phone : old('shipping_phone') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ nhận hàng (Chi tiết) <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" class="form-control" rows="3" required>{{ Auth::check() ? Auth::user()->address : old('shipping_address') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ email <span class="text-danger">*</span></label>
                                <input type="email" name="shipping_email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : old('shipping_email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea name="order_notes" class="form-control" rows="2" placeholder="Ví dụ: Giao hàng vào giờ hành chính...">{{ old('order_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Phương thức thanh toán</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label fw-bold" for="cod">
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                                <label class="form-check-label fw-bold" for="vnpay">
                                    Chuyển khoản ngân hàng (VNPay)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: Tóm tắt đơn hàng --}}
                <div class="col-md-5">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Đơn hàng của bạn</h5>

                            @php $total = 0; @endphp
                            @foreach($cart as $details)
                                @php $total += $details['price'] * $details['quantity']; @endphp
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset($details['image']) }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                        <div>
                                            <h6 class="mb-0 fs-6">{{ $details['name'] }}</h6>
                                            <small class="text-muted">SL: {{ $details['quantity'] }}</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ</span>
                                </div>
                            @endforeach

                            <hr>
                            @if(Auth::check())
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mã giảm giá</label>
                                    <select name="coupon_code" class="form-select">
                                        <option value="">Không dùng mã</option>
                                        @foreach($availableCoupons as $coupon)
                                            <option value="{{ $coupon->code }}" {{ old('coupon_code') === $coupon->code ? 'selected' : '' }}>
                                                {{ $coupon->code }} -
                                                @if($coupon->promotion->discount_type === 'percent')
                                                    giảm {{ $coupon->promotion->discount_value }}%
                                                @else
                                                    giảm {{ number_format($coupon->promotion->discount_value, 0, ',', '.') }}đ
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Đổi điểm tích lũy
                                        <span class="text-muted">({{ Auth::user()->reward_points }} điểm)</span>
                                    </label>
                                    <input type="number"
                                           name="used_reward_points"
                                           class="form-control"
                                           min="0"
                                           max="{{ Auth::user()->reward_points }}"
                                           value="{{ old('used_reward_points', 0) }}">
                                    <small class="text-muted">Gợi ý: 1 điểm = 10.000đ, tối đa giảm 30% đơn hàng.</small>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Đăng nhập để dùng mã giảm giá và tích điểm sau khi mua hàng.
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5 fw-bold">Tổng cộng:</span>
                                <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>

                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold">
                                ĐẶT HÀNG NGAY
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
