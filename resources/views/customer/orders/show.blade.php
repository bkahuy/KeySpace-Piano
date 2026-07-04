@extends('layout.index')
{{-- Đổi lại tên layout cho khớp với thư mục frontend của bạn nếu cần --}}

@section('title', 'Chi tiết Đơn hàng #' . $order->order_code)

@section('content')
    <div class="container py-5">
        {{-- Nút quay lại và Tiêu đề --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fa-solid fa-file-invoice me-2 text-primary"></i>
                Chi tiết đơn hàng: <span class="text-danger">{{ $order->order_code }}</span>
            </h4>
            <a href="{{ route('customer.orders.history') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Lịch sử mua hàng
            </a>
        </div>

        <div class="row">
            {{-- CỘT TRÁI: Thông tin giao hàng & Trạng thái --}}
            <div class="col-lg-4 mb-4">
                {{-- Thẻ Trạng thái đơn hàng --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-uppercase text-muted">Trạng thái hiện tại</h6>
                        <div class="d-flex align-items-center">
                            @php
                                $badgeClass = 'secondary';
                                $badgeText = 'Không xác định';
                                $icon = 'fa-circle-question';

                                if($order->status == 'pending') { $badgeClass = 'warning text-dark'; $badgeText = 'Chờ duyệt đơn'; $icon = 'fa-clock'; }
                                elseif($order->status == 'processing') { $badgeClass = 'info text-dark'; $badgeText = 'Đang xử lý'; $icon = 'fa-box'; }
                                elseif($order->status == 'shipping') { $badgeClass = 'primary'; $badgeText = 'Đang giao hàng'; $icon = 'fa-truck-fast'; }
                                elseif($order->status == 'delivered') { $badgeClass = 'success'; $badgeText = 'Hoàn thành'; $icon = 'fa-circle-check'; }
                                elseif($order->status == 'cancelled') { $badgeClass = 'danger'; $badgeText = 'Đã hủy'; $icon = 'fa-circle-xmark'; }
                            @endphp

                            <i class="fa-solid {{ $icon }} fa-2x text-{{ str_replace(' text-dark', '', $badgeClass) }} me-3"></i>
                            <div>
                                <h5 class="mb-0 text-{{ str_replace(' text-dark', '', $badgeClass) }} fw-bold">{{ $badgeText }}</h5>
                                <small class="text-muted">Cập nhật lúc: {{ $order->updated_at->format('H:i d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>


                @if($order->status == 'pending')
                    <div class="mb-4">
                        {{-- 1. Nút bấm mở Popup --}}
                        <button type="button" class="btn btn-outline-danger w-100 fw-bold py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                            <i class="fa-solid fa-xmark me-2"></i> HỦY ĐƠN HÀNG NÀY
                        </button>

                        {{-- 2. Khung Popup Bootstrap (Modal) --}}
                        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">

                                    {{-- Tiêu đề Popup --}}
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title text-danger fw-bold" id="cancelOrderModalLabel">
                                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Xác nhận hủy đơn hàng
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>

                                    {{-- Nội dung hỏi khách hàng --}}
                                    <div class="modal-body text-start text-dark fs-6 py-4">
                                        Bạn có chắc chắn muốn hủy đơn hàng <strong class="text-primary">#{{ $order->order_code }}</strong> không?
                                        <br>
                                        <span class="text-muted small"><i class="fa-solid fa-circle-info me-1 mt-2"></i> Lưu ý: Hành động này không thể hoàn tác.</span>
                                    </div>

                                    {{-- Các nút hành động --}}
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">Không, giữ lại đơn</button>

                                        {{-- FORM HỦY ĐƠN THẬT SỰ ĐƯỢC GIẤU VÀO ĐÂY --}}
                                        <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger fw-bold px-4">
                                                Vâng, Hủy đơn ngay!
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Thẻ Thông tin người nhận --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-location-dot me-2"></i>Địa chỉ nhận hàng</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 lh-lg">
                            <li><strong>Họ tên:</strong> {{ $order->shipping_name }}</li>
                            <li><strong>Điện thoại:</strong> <span class="text-primary fw-bold">{{ $order->shipping_phone }}</span></li>
                            <li><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</li>
                            <li><strong>Ngày đặt:</strong> {{ $order->created_at->format('H:i d/m/Y') }}</li>
                            <li><strong>Thanh toán:</strong>
                                {{ $order->payment_method == 'cod' ? 'Tiền mặt khi nhận hàng (COD)' : 'Chuyển khoản' }}
                            </li>
                            @if($order->order_notes)
                                <li class="mt-2 p-2 bg-light rounded text-danger border-start border-danger border-3">
                                    <strong>Ghi chú:</strong> <i>"{{ $order->order_notes }}"</i>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: Danh sách đàn Piano đã mua --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-box-open me-2"></i>Sản phẩm trong đơn</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;" class="ps-4">Hình ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            @php
                                                // Lấy ảnh chính của sản phẩm
                                                $primaryImage = $item->path_img;
                                                $imagePath = $primaryImage ? asset($item->path_img) : asset('frontend/images/no-image.jpg');
                                            @endphp
                                            <img src="{{ $imagePath }}" alt="{{ $item->product_name }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        </td>
                                        <td class="text-center">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                                        <td class="text-center fw-bold text-secondary">x{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold text-danger pe-4">
                                            {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}đ
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold fs-5 pt-3 border-bottom-0">Tổng thanh toán:</td>
                                    <td class="text-end fw-bold fs-4 text-danger pt-3 pe-4 border-bottom-0">
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
    </div>
@endsection
