@extends('admin.layouts.master')
@section('title', 'Tổng quan hệ thống')

@section('content')
    {{-- HÀNG 1: 4 Thẻ Thống Kê Chính --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary text-white shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Tổng Doanh Thu</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h3>
                    </div>
                    <i class="fa-solid fa-sack-dollar fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success text-white shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Tổng Đơn Hàng</h6>
                        <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                    </div>
                    <i class="fa-solid fa-cart-shopping fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning text-dark shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Đàn Piano (Kho)</h6>
                        <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                    </div>
                    <i class="fa-solid fa-music fa-3x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info text-white shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase fw-bold mb-2">Khách Hàng</h6>
                        <h3 class="fw-bold mb-0">{{ $totalCustomers }}</h3>
                    </div>
                    <i class="fa-solid fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- HÀNG 2: Nhắc việc & Bảng Đơn hàng mới --}}
    <div class="row">
        {{-- CỘT TRÁI: Nhắc việc Admin --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-bell text-warning me-2"></i>Cần xử lý hôm nay</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <span class="fs-6 fw-semibold text-muted">Đơn chờ duyệt mới:</span>
                        <span class="badge bg-danger rounded-pill fs-6 px-3">{{ $pendingOrders }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <span class="fs-6 fw-semibold text-muted">Đơn đang đóng gói:</span>
                        <span class="badge bg-info rounded-pill fs-6 px-3">{{ $processingOrders }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2">
                        <span class="fs-6 fw-semibold text-muted">Đơn đang trên đường giao:</span>
                        <span class="badge bg-primary rounded-pill fs-6 px-3">{{ $shippingOrders }}</span>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100 fw-bold">Đi tới quản lý đơn hàng <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: 5 Đơn hàng mới nhất --}}
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-clock text-success me-2"></i>Đơn hàng vừa đặt</h6>
                    <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted small">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end pe-3">Lúc đặt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-primary px-3">{{ $order->order_code }}</td>
                                    <td>{{ $order->shipping_name }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td class="text-center">
                                    <span class="badge bg-{{
                                        $order->status == 'pending' ? 'warning text-dark' :
                                        ($order->status == 'processing' ? 'info text-dark' :
                                        ($order->status == 'shipping' ? 'primary' :
                                        ($order->status == 'delivered' ? 'success' :
                                        ($order->status == 'cancelled' ? 'danger' : 'primary'))))
                                    }}">
                                        {{ match($order->status) {
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipping' => 'Đang giao hàng',
                                            'delivered' => 'Đã giao hàng',
                                            'cancelled' => 'Đã hủy',
                                            default => strtoupper($order->status)
                                        } }}
                                    </span>
                                    </td>
                                    <td class="text-end text-muted pe-3">{{ $order->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
