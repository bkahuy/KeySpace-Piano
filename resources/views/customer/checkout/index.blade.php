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

        <form action="{{ route('checkout.process') }}" method="POST" novalidate id="checkout-form">
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
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="shipping_phone" class="form-control" value="{{ Auth::check() ? Auth::user()->phone : old('shipping_phone') }}" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ nhận hàng (Chi tiết) <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" class="form-control" rows="3" required>{{ Auth::check() ? Auth::user()->address : old('shipping_address') }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ email <span class="text-danger">*</span></label>
                                <input type="email" name="shipping_email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : old('shipping_email') }}" required>
                                <div class="invalid-feedback"></div>
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
                                    <div class="invalid-feedback"></div>
                                    <small class="text-muted">Gợi ý: 1 điểm = 10.000đ, tối đa giảm 30% đơn hàng.</small>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Đăng nhập để dùng mã giảm giá và tích điểm sau khi mua hàng.
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Tạm tính:</span>
                                <span id="subtotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 text-success" id="coupon-discount-row" style="display: none !important;">
                                <span class="fw-bold">Giảm giá (Mã):</span>
                                <span id="coupon-discount">0đ</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 text-success" id="points-discount-row" style="display: none !important;">
                                <span class="fw-bold">Giảm giá (Điểm):</span>
                                <span id="points-discount">0đ</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5 fw-bold">Tổng cộng:</span>
                                <span class="fs-4 fw-bold text-danger" id="final-total">{{ number_format($total, 0, ',', '.') }}đ</span>
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

    <script>
        // Validation
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let isValid = true;
            const form = this;

            // Xóa các thông báo lỗi cũ
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Validate họ tên
            const name = form.querySelector('[name="shipping_name"]');
            if (!name.value.trim()) {
                showError(name, 'Vui lòng nhập họ và tên người nhận');
                isValid = false;
            }

            // Validate số điện thoại
            const phone = form.querySelector('[name="shipping_phone"]');
            const phoneRegex = /^(0|\+84)[0-9]{9,10}$/;
            if (!phone.value.trim()) {
                showError(phone, 'Vui lòng nhập số điện thoại');
                isValid = false;
            } else if (!phoneRegex.test(phone.value.trim())) {
                showError(phone, 'Số điện thoại không hợp lệ (VD: 0912345678)');
                isValid = false;
            }

            // Validate địa chỉ
            const address = form.querySelector('[name="shipping_address"]');
            if (!address.value.trim()) {
                showError(address, 'Vui lòng nhập địa chỉ nhận hàng');
                isValid = false;
            }

            // Validate email
            const email = form.querySelector('[name="shipping_email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim()) {
                showError(email, 'Vui lòng nhập địa chỉ email');
                isValid = false;
            } else if (!emailRegex.test(email.value.trim())) {
                showError(email, 'Địa chỉ email không hợp lệ');
                isValid = false;
            }

            // Validate điểm thưởng
            const rewardPointsInput = form.querySelector('[name="used_reward_points"]');
            if (rewardPointsInput) {
                const usedPoints = parseInt(rewardPointsInput.value) || 0;
                const maxPoints = parseInt(rewardPointsInput.getAttribute('max')) || 0;

                if (usedPoints < 0) {
                    showError(rewardPointsInput, 'Số điểm không được âm');
                    isValid = false;
                } else if (usedPoints > maxPoints) {
                    showError(rewardPointsInput, `Bạn chỉ có ${maxPoints} điểm, không thể sử dụng ${usedPoints} điểm`);
                    isValid = false;
                }
            }

            if (isValid) {
                form.submit();
            } else {
                // Scroll đến lỗi đầu tiên
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });

        function showError(element, message) {
            element.classList.add('is-invalid');
            const feedback = element.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = message;
                feedback.style.display = 'block';
            }
        }

        // Xóa lỗi khi người dùng bắt đầu nhập
        document.querySelectorAll('#checkout-form input, #checkout-form textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });
        });
    </script>

    @if(Auth::check())
    <script>
        const ORIGINAL_TOTAL = {{ $total }};
        const AVAILABLE_COUPONS = {!! json_encode($availableCoupons->map(function($coupon) {
            return [
                'code' => $coupon->code,
                'discount_type' => $coupon->promotion->discount_type,
                'discount_value' => $coupon->promotion->discount_value,
            ];
        })->keyBy('code')) !!};
        const MAX_POINTS = {{ Auth::user()->reward_points }};
        const POINT_VALUE = 10000; // 1 điểm = 10,000đ
        const MAX_POINTS_DISCOUNT_PERCENT = 0.3; // Tối đa giảm 30%

        function formatNumber(num) {
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + 'đ';
        }

        function calculateTotal() {
            let total = ORIGINAL_TOTAL;
            let couponDiscount = 0;
            let pointsDiscount = 0;

            // Tính giảm giá từ mã coupon
            const selectedCoupon = document.querySelector('select[name="coupon_code"]').value;
            if (selectedCoupon && AVAILABLE_COUPONS[selectedCoupon]) {
                const coupon = AVAILABLE_COUPONS[selectedCoupon];
                if (coupon.discount_type === 'percent') {
                    couponDiscount = total * (coupon.discount_value / 100);
                } else {
                    couponDiscount = coupon.discount_value;
                }
            }

            // Áp dụng giảm giá từ coupon trước
            total = total - couponDiscount;

            // Tính giảm giá từ điểm tích lũy
            const usedPoints = parseInt(document.querySelector('input[name="used_reward_points"]').value) || 0;
            if (usedPoints > 0) {
                pointsDiscount = Math.min(usedPoints * POINT_VALUE, total * MAX_POINTS_DISCOUNT_PERCENT);
            }

            // Áp dụng giảm giá từ điểm
            total = total - pointsDiscount;

            // Đảm bảo tổng không âm
            total = Math.max(0, total);

            // Cập nhật hiển thị
            document.getElementById('coupon-discount').textContent = '- ' + formatNumber(couponDiscount);
            document.getElementById('points-discount').textContent = '- ' + formatNumber(pointsDiscount);
            document.getElementById('final-total').textContent = formatNumber(total);

            // Hiển thị/ẩn dòng giảm giá
            document.getElementById('coupon-discount-row').style.display = couponDiscount > 0 ? 'flex' : 'none';
            document.getElementById('points-discount-row').style.display = pointsDiscount > 0 ? 'flex' : 'none';
        }

        // Lắng nghe sự kiện thay đổi
        document.querySelector('select[name="coupon_code"]').addEventListener('change', calculateTotal);
        document.querySelector('input[name="used_reward_points"]').addEventListener('input', calculateTotal);

        // Tính toán ban đầu khi trang load
        calculateTotal();
    </script>
    @endif
@endsection
