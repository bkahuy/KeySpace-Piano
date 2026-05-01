@extends('admin.layouts.master')
@section('title', 'Quản lý Đơn hàng')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-cart-flatbed me-2 text-primary"></i>Danh sách Đơn hàng</h5>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('orders.index') }}" method="GET">


                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm mã đơn, tên, SĐT..." value="{{ request('keyword') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Chờ duyệt đơn</option>
                                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select name="payment_method" class="form-select">
                                <option value="">-- Tất cả thanh toán --</option>
                                <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán COD</option>
                                <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>Thanh toán VNPay</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Ngày</span>
                                <input type="date" name="created_at" class="form-control" value="{{ request('created_at') }}">
                            </div>
                        </div>


                        <div class="col-md-4 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary w-50 fw-bold">
                                <i class="fa-solid fa-filter me-1"></i> Lọc đơn
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-light border w-50 text-center">
                                <i class="fa-solid fa-rotate-right me-1"></i> Làm mới
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>


        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-center fw-bold text-primary">{{ $order->order_code }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->shipping_name }}</div>
                                <small class="text-muted">{{ $order->shipping_address }}</small>
                            </td>
                            <td>{{ $order->shipping_phone }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>

                            {{-- Trạng thái thanh toán --}}
                            <td class="text-center">
                                @if($order->payment_method == 'cod')
                                    <span class="badge bg-secondary">COD</span>
                                @else
                                    <span class="badge bg-info text-dark">Chuyển khoản</span>
                                @endif
                            </td>

                            {{-- Trạng thái Đơn hàng --}}
                            <td class="text-center">
                                @php
                                    $statusClass = 'secondary';
                                    $statusText = 'Không xác định';

                                    switch($order->status) {
                                        case 'pending':
                                            $statusClass = 'warning text-dark';
                                            $statusText = 'Chờ xử lý';
                                            break;
                                        case 'processing':
                                            $statusClass = 'info text-dark';
                                            $statusText = 'Đang xử lý';
                                            break;
                                        case 'shipping':
                                            $statusClass = 'primary';
                                            $statusText = 'Đang giao hàng';
                                            break;
                                        case 'delivered':
                                            $statusClass = 'success';
                                            $statusText = 'Hoàn thành';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'danger';
                                            $statusText = 'Đã hủy';
                                            break;
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }} rounded-pill px-3 py-2">
                                {{ $statusText }}
                            </span>
                            </td>

                            {{-- Nút Xem chi tiết --}}
                            <td class="text-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có đơn hàng nào trên hệ thống.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        @if($orders->hasPages())
            <div class="card-footer bg-white pt-4 pb-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
