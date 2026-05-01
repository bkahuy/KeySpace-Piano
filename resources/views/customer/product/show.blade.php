@extends('layout.index')
@section('title', $product->name . ' - Key Space')
@section('content')
    <div class="container ">

        {{-- Breadcrumb (Thanh điều hướng) --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row mt-4">
            {{-- CỘT TRÁI: THƯ VIỆN ẢNH --}}
            <div class="col-md-6 mb-4">
                {{-- Ảnh lớn --}}
                <div class="card mb-3">
                    @php
                        // Lấy ảnh chính (is_primary = true), nếu không có thì lấy ảnh đầu tiên trong mảng
                        $mainImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    @endphp

                    <img id="mainImage"
                         src="{{ $mainImage ? asset($mainImage->image_path) : asset('images/no-image.jpg') }}"
                         class="img-fluid rounded"
                         alt="{{ $product->name }}"
                         style="width: 100%; height: 400px; object-fit: cover;">
                </div>

                {{-- Danh sách ảnh nhỏ (Thumbnails) --}}
                <div class="d-flex gap-2 overflow-auto">
                    @foreach($product->images as $image)
                        <img src="{{ asset($image->image_path) }}"
                             class="img-thumbnail thumbnail-img"
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                             onclick="changeImage(this.src)"
                             alt="Thumbnail">
                    @endforeach
                </div>
            </div>

            {{-- CỘT PHẢI: THÔNG TIN SẢN PHẨM --}}
            <div class="col-md-6">
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <p class="text-muted">Mã SP: {{ $product->sku }} | Thương hiệu: <span class="fw-bold">{{ $product->brand->name }}</span></p>

                {{-- Khu vực Giá --}}
                <div class="mb-4 bg-light p-3 rounded">
                    <h2 class="text-danger fw-bold mb-0">{{ number_format($product->price, 0, ',', '.') }} VNĐ</h2>
                </div>

                {{-- Thông số cơ bản --}}
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i><strong>Tình trạng:</strong> {{ $product->condition == 'new' ? 'Mới 100%' : 'Đã qua sử dụng (Like New)' }}</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i><strong>Bảo hành:</strong> {{ $product->warranty_period }} tháng</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i><strong>Màu sắc:</strong> {{ $product->color ?? 'Đang cập nhật' }}</li>
                    <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i><strong>Kho:</strong>
                        @if($product->stock_quantity > 0)
                            <span class="text-success">Còn hàng ({{ $product->stock_quantity }})</span>
                        @else
                            <span class="text-danger">Tạm hết hàng</span>
                        @endif
                    </li>
                </ul>

                <p>{{ $product->short_description }}</p>

                {{-- Form thêm vào giỏ hàng --}}
                <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                    @csrf
                    {{-- Input ẩn để gửi ID sản phẩm lên Controller --}}
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label for="quantity" class="fw-bold">Số lượng:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 80px;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                                name="redirect_to"
                                value="checkout"
                                class="btn btn-danger btn-lg flex-fill"
                            {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-bolt me-2"></i> MUA NGAY
                        </button>

                        <button type="submit"
                                name="redirect_to"
                                value="cart"
                                class="btn btn-outline-danger btn-lg flex-fill"
                            {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus me-2"></i> THÊM GIỎ
                        </button>
                    </div>


                    <a href="https://zalo.me/84837607568" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-lg w-100 mt-2">
                        <i class="fa-solid fa-phone me-2"></i>
                        LIÊN HỆ TƯ VẤN
                    </a>

                </form>
            </div>
        </div>

        {{-- PHẦN MÔ TẢ CHI TIẾT --}}
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="border-bottom pb-2">Mô tả chi tiết</h4>
                <div class="mt-3">
                    {{-- Dùng {!! !!} thay vì {{ }} để render mã HTML nếu admin nhập bằng CKEditor --}}
                    {!! $product->detailed_description !!}
                </div>
            </div>
        </div>

        {{-- ================= KHU VỰC ĐÁNH GIÁ ================= --}}
        <div class="mt-5 border-top pt-4">
            <h4 class="mb-4">Đánh giá khách hàng ({{ $product->reviews->count() }})</h4>

            {{-- Form nhập đánh giá --}}
            <div class="card bg-light border-0 mb-4">
                <div class="card-body">
                    @auth
                        <form action="{{ route('products.review', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chất lượng sản phẩm:</label>
                                {{-- Chọn sao bằng Select (Có thể nâng cấp thành HTML CSS bấm sao sau) --}}
                                <select name="rating" class="form-select w-auto" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (5 Sao - Tuyệt vời)</option>
                                    <option value="4">⭐⭐⭐⭐ (4 Sao - Rất tốt)</option>
                                    <option value="3">⭐⭐⭐ (3 Sao - Bình thường)</option>
                                    <option value="2">⭐⭐ (2 Sao - Kém)</option>
                                    <option value="1">⭐ (1 Sao - Tệ)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Bình luận của bạn:</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận của bạn về cây đàn này..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    @else
                        <div class="text-center py-3">
                            <p class="mb-2">Vui lòng đăng nhập để gửi đánh giá của bạn.</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập ngay</a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Danh sách hiển thị các đánh giá cũ --}}
            <div class="reviews-list">
                @forelse($product->reviews as $review)
                    <div class="d-flex mb-4 border-bottom pb-3">
                        <div class="me-3">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $review->user->name }}</h6>
                            <div class="text-warning mb-2 small">
                                {{-- Vòng lặp in ra số sao vàng --}}
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star text-muted"></i>
                                    @endif
                                @endfor
                                <span class="text-muted ms-2">{{ $review->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
                @endforelse
            </div>
        </div>

    </div>
@endsection

{{-- Script Javascript để đổi ảnh khi click thumbnail --}}
@push('scripts')
    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
@endpush
