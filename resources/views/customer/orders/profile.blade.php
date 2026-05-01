@extends('layout.index')

@section('title', 'Tài khoản của tôi - KeySpace')

@section('content')
    @php
        $user = auth()->user();

        $statusMap = [
            'pending' => ['class' => 'warning text-dark', 'text' => 'Chờ duyệt'],
            'processing' => ['class' => 'info text-dark', 'text' => 'Chờ xử lý'],
            'shipping' => ['class' => 'primary', 'text' => 'Đang giao'],
            'delivered' => ['class' => 'success', 'text' => 'Hoàn thành'],
            'cancelled' => ['class' => 'danger', 'text' => 'Đã hủy'],
        ];
    @endphp

    <div class="container py-5">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="fa-solid fa-circle-user me-2"></i>Tài khoản của tôi
                        </h6>
                    </div>

                    <div class="list-group list-group-flush" role="tablist">
                        <button class="list-group-item list-group-item-action"
                                id="profile-tab"
                                data-bs-toggle="list"
                                data-bs-target="#profile-content"
                                type="button"
                                role="tab">
                            <i class="fa-solid fa-user me-2"></i> Thông tin cá nhân
                        </button>

                        <button class="list-group-item list-group-item-action active"
                                id="orders-tab"
                                data-bs-toggle="list"
                                data-bs-target="#orders-content"
                                type="button"
                                role="tab">
                            <i class="fa-solid fa-clipboard-list me-2"></i> Lịch sử đơn hàng
                        </button>

                        <button class="list-group-item list-group-item-action"
                                id="coupons-tab"
                                data-bs-toggle="list"
                                data-bs-target="#coupons-content"
                                type="button"
                                role="tab">
                            <i class="fa-solid fa-ticket me-2"></i> Mã giảm giá của tôi
                        </button>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action text-danger fw-bold w-100 text-start border-0">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Nội dung --}}
            <div class="col-lg-9">
                <div class="tab-content">
                    {{-- Tab thông tin cá nhân --}}
                    <div class="tab-pane fade" id="profile-content" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="fw-bold text-primary mb-4">Hồ sơ cá nhân</h4>

                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Họ và Tên <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                        </div>

                                        <div class="col-md-6 mt-3 mt-md-0">
                                            <label class="form-label fw-bold">Số điện thoại</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Để nhận hàng">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Địa chỉ giao hàng</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Số nhà, đường, xã/phường, quận/huyện...">
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="fw-bold mb-3">Đổi mật khẩu <span class="text-muted fs-6">(Bỏ trống nếu không đổi)</span></h5>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Mật khẩu hiện tại</label>
                                        <input type="password" name="current_password" class="form-control">
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Mật khẩu mới</label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>

                                        <div class="col-md-6 mt-3 mt-md-0">
                                            <label class="form-label text-muted">Nhập lại mật khẩu mới</label>
                                            <input type="password" name="new_password_confirmation" class="form-control">
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Tab lịch sử đơn hàng --}}
                    <div class="tab-pane fade show active" id="orders-content" role="tabpanel">
                        <h4 class="fw-bold mb-4">Lịch sử mua hàng</h4>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Mã Đơn</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center pe-4">Thao tác</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @forelse($orders as $order)
                                            @php
                                                $status = $statusMap[$order->status] ?? [
                                                    'class' => 'secondary',
                                                    'text' => 'Không xác định',
                                                ];
                                            @endphp

                                            <tr>
                                                <td class="ps-4 fw-bold text-primary">{{ $order->order_code }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>

                                                <td class="text-center">
                                                        <span class="badge bg-{{ $status['class'] }} rounded-pill px-3 py-2">
                                                            {{ $status['text'] }}
                                                        </span>
                                                </td>

                                                <td class="text-center pe-4">
                                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                        Xem chi tiết
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="fa-solid fa-box-open fa-3x mb-3 text-secondary opacity-50"></i>
                                                    <p class="mb-0">Bạn chưa có đơn hàng nào.</p>
                                                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Mua sắm ngay</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($orders->hasPages())
                                <div class="card-footer bg-white pt-4 pb-3">
                                    {{ $orders->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{--Mã giảm giá--}}
                    <div class="tab-pane fade" id="coupons-content" role="tabpanel">
                        <h4 class="fw-bold mb-4">Mã giảm giá của tôi</h4>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @forelse($availableCoupons as $coupon)
                                    <div class="border rounded p-3 mb-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold text-danger fs-5">{{ $coupon->code }}</div>
                                                <div>{{ $coupon->promotion->name }}</div>
                                                <small class="text-muted">
                                                    Đơn tối thiểu:
                                                    {{ number_format($coupon->promotion->min_order_amount, 0, ',', '.') }}đ
                                                </small>
                                            </div>

                                            <div class="text-end">
                                                <span class="badge bg-success mb-2">Có thể dùng</span>
                                                <div class="fw-bold">
                                                    @if($coupon->promotion->discount_type === 'percent')
                                                        Giảm {{ $coupon->promotion->discount_value }}%
                                                    @else
                                                        Giảm {{ number_format($coupon->promotion->discount_value, 0, ',', '.') }}đ
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        Bạn chưa có mã giảm giá khả dụng.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
