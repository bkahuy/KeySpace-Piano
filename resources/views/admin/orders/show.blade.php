@extends('admin.layouts.master')
@section('title', 'Chi tiết Đơn hàng #' . $order->order_code)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="fa-solid fa-file-invoice me-2 text-primary"></i>
            Đơn hàng: <span class="text-danger">{{ $order->order_code }}</span>
        </h4>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        {{-- CỘT TRÁI: Thông tin khách hàng & Form cập nhật trạng thái --}}
        <div class="col-md-4">

            {{-- CARD 1: Cập nhật trạng thái --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-rotate me-2"></i>Trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <select name="status" class="form-select form-select-lg {{
                                $order->status == 'pending' ? 'text-warning fw-bold' :
                                ($order->status == 'processing' ? 'text-info fw-bold' :
                                ($order->status == 'shipping' ? 'text-info fw-bold' :
                                ($order->status == 'delivered' ? 'text-success fw-bold' :
                                ($order->status == 'cancelled' ? 'text-danger fw-bold' : 'text-primary fw-bold'))))
                            }}">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ duyệt đơn</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> CẬP NHẬT
                        </button>
                    </form>
                </div>
            </div>

            {{-- CARD 2: Thông tin giao hàng --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-location-dot me-2"></i>Thông tin người nhận</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 lh-lg">
                        <li><strong>Họ tên:</strong> {{ $order->shipping_name }}</li>
                        <li><strong>Điện thoại:</strong> <span class="text-primary fw-bold">{{ $order->shipping_phone }}</span></li>
                        <li><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</li>
                        <li><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Phương thức TT:</strong>
                            {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' }}
                        </li>
                        @if($order->order_notes)
                            <li class="mt-2 text-danger">
                                <strong>Ghi chú:</strong> <i>"{{ $order->order_notes }}"</i>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: Danh sách sản phẩm khách đã mua --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-box-open me-2"></i>Chi tiết sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th class="text-center">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end pe-4">Thành tiền</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        {{-- Lấy ảnh chính của sản phẩm --}}
                                        @php
                                            $primaryImage = $item->product->images->where('is_primary', true)->first();
                                            $imagePath = $primaryImage ? asset($primaryImage->image_path) : asset('frontend/images/no-image.jpg');
                                        @endphp
                                        <img src="{{ $imagePath }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->product->name }}</div>
                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    </td>
                                    <td class="text-center">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                                    <td class="text-center fw-bold">x{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-danger pe-4">
                                        {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold fs-5 pt-3">Tổng cộng:</td>
                                <td class="text-end fw-bold fs-4 text-danger pt-3 pe-4">
                                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
