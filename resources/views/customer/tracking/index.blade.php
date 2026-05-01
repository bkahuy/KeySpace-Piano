@extends('layout.index')
@section('title', 'Tra cứu đơn hàng')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Form nhập thông tin tra cứu --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary"><i class="fa-solid fa-magnifying-glass-location"></i> Tra Cứu Đơn Hàng</h3>
                            <p class="text-muted">Kiểm tra tình trạng đơn hàng KeySpace của bạn</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('tracking.track') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Mã đơn hàng</label>
                                    <input type="text" name="order_code" class="form-control" placeholder="VD: ORD-123456-ABCDE" value="{{ old('order_code', request('order_code')) }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Email đặt hàng</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email sử dụng khi đặt hàng" value="{{ old('email', request('email')) }}" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">Tra cứu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kết quả hiển thị (Chỉ hiện khi có biến $order từ Controller trả về) --}}
                @if(isset($order))
                    <div class="card border-0 shadow-sm rounded-4 mt-4">
                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold m-0">Chi tiết đơn hàng: <span class="text-primary">{{ $order->order_code }}</span></h5>
                                <span class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            {{-- Hiển thị Status Đơn giản --}}
                            <div class="text-center my-4">
                                <h6 class="text-muted mb-2">Trạng thái hiện tại:</h6>
                                <span class="badge {{ $statusBadge }} fs-5 px-4 py-2">
                                {{ $statusText }}
                            </span>
                            </div>

                            {{-- Thông tin tóm tắt --}}
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold text-muted">Phương thức thanh toán</h6>
                                    <p class="mb-0">
                                        {{ $order->payment_method == 'vnpay' ? 'Thanh toán VNPay' : 'Thanh toán khi nhận hàng (COD)' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3 text-md-end">
                                    <h6 class="fw-bold text-muted">Tổng tiền</h6>
                                    <h5 class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</h5>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
