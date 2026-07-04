@extends('layout.index')
@section('title', 'Giỏ hàng của bạn')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>

        @if(count($cart) > 0)
            <div class="row">
                {{-- Bảng chi tiết giỏ hàng --}}
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th style="width: 120px;">Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Xóa</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $details)
                                @php
                                    $subtotal = $details['price'] * $details['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                            <a href="{{ route('product.show', $details['slug']) }}" class="text-decoration-none text-dark fw-bold">
                                                {{ $details['name'] }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-danger fw-bold">{{ number_format($details['price'], 0, ',', '.') }}đ</td>
                                    <td>
                                        <form action="{{ route('cart.update') }}" method="POST" class="m-0 d-flex justify-content-center">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">

                                            {{-- Thêm thuộc tính onchange="this.form.submit()" vào thẻ input --}}
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}"
                                                   class="form-control text-center form-control-sm"
                                                   min="1" style="width: 70px;"
                                                   onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                    <td class="text-center">
                                        {{-- 1. Nút bấm để mở Popup (Không chuyển trang ngay lập tức nữa) --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $id }}" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        {{-- 2. Khung Popup Modal (Sẽ bị ẩn đi cho đến khi bấm nút ở trên) --}}
                                        {{-- Lưu ý: ID của modal phải gắn thêm {{ $id }} để phân biệt các sản phẩm với nhau --}}
                                        <div class="modal fade" id="deleteModal-{{ $id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    {{-- Tiêu đề Popup --}}
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title text-danger fw-bold" id="deleteModalLabel-{{ $id }}">
                                                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Xác nhận xóa
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>

                                                    {{-- Nội dung hỏi --}}
                                                    <div class="modal-body text-start text-dark fs-6">
                                                        Bạn có chắc chắn muốn xóa đàn <strong class="text-primary">{{ $details['name'] }}</strong> khỏi giỏ hàng không?
                                                    </div>

                                                    {{-- Các nút hành động --}}
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>

                                                        {{-- ĐÂY MỚI LÀ NÚT XÓA THẬT (Trỏ link về Route xóa) --}}
                                                        <a href="{{ route('cart.remove', $id) }}" class="btn btn-danger">Đồng ý xóa</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Cột tổng kết và Thanh toán --}}
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Tổng đơn hàng</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Tạm tính:</span>
                                <span class="fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5 fw-bold">Tổng cộng:</span>
                                <span class="fs-5 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="btn btn-danger btn-lg w-100 fw-bold">
                                TIẾN HÀNH THANH TOÁN
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Tiếp tục mua hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa-solid fa-cart-shopping text-muted" style="font-size: 80px;"></i>
                <h4 class="mt-3">Giỏ hàng của bạn đang trống</h4>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Quay lại cửa hàng</a>
            </div>
        @endif
    </div>
@endsection
