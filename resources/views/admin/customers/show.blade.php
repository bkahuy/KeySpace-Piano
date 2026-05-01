@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">Hồ sơ Khách hàng</h4>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN CÁ NHÂN --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-id-card"></i> Thông tin cá nhân</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                <i class="fa-solid fa-user fs-1 text-secondary"></i>
                            </div>
                            <h5 class="mt-3 fw-bold">{{ $customer->name }}</h5>
                            <span class="badge bg-success">Khách hàng</span>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0"><strong>Email:</strong> {{ $customer->email }}</li>
                            <li class="list-group-item px-0"><strong>Điện thoại:</strong> {{ $customer->phone ?? 'Chưa cập nhật' }}</li>
                            <li class="list-group-item px-0"><strong>Ngày tham gia:</strong> {{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : 'N/A' }}</li>
                            <li class="list-group-item px-0"><strong>Tổng đơn đã đặt:</strong> <span class="badge bg-info text-dark">{{ $customer->orders->count() }} đơn</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: LỊCH SỬ MUA HÀNG --}}
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-cart-shopping"></i> Lịch sử mua hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($customer->orders as $order)
                                    <tr>
                                        <td class="fw-bold">{{ $order->order_code }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                        <td>
                                            @if($order->payment_method == 'vnpay')
                                                <span class="badge bg-primary">VNPay</span>
                                            @else
                                                <span class="badge bg-secondary">COD</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info text-dark">Đang giao</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success">Hoàn thành</span>
                                            @else
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Khách hàng này chưa có đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
