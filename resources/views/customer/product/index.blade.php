@extends('layout.index')
@section('title', 'Danh sách Đàn Piano - KeySpace')

@section('content')
    <div class="container py-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                @if(request('keyword'))
                    <h3 class="fw-bold mb-0">
                        Kết quả tìm kiếm: <span class="text-primary">"{{ request('keyword') }}"</span>
                    </h3>
                @else
                    <h2 class="fw-bold mb-0">Khám phá Đàn Piano</h2>
                @endif
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <span class="text-muted">Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm</span>
            </div>
        </div>

        <div class="row">
            {{-- CỘT TRÁI: BỘ LỌC TÌM KIẾM --}}
            <div class="col-lg-3 mb-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px; z-index: 99;">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-filter me-2"></i>Bộ lọc tìm kiếm</h6>
                    </div>
                    <div class="card-body">
                        {{-- Form Lọc (Bắt buộc dùng method="GET") --}}
                        <form action="{{ route('product.index') }}" method="GET">
                            @if(request('keyword'))
                                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                            @endif

                            {{-- 1. Sắp xếp --}}
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Sắp xếp theo</label>
                                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống Thấp</option>
                                </select>
                            </div>

                            {{-- 2. Danh mục --}}
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Danh mục</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 3. Thương hiệu --}}
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Thương hiệu</label>
                                <select name="brand" class="form-select form-select-sm">
                                    <option value="">Tất cả thương hiệu</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 4. Tình trạng --}}
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Tình trạng</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condAll" value="" {{ request('condition') == '' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condAll">Tất cả</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condNew" value="new" {{ request('condition') == 'new' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condNew">Mới (New)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" id="condUsed" value="used" {{ request('condition') == 'used' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condUsed">Cũ (Used)</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa-solid fa-magnifying-glass me-2"></i>ÁP DỤNG LỌC</button>

                            @if(request()->anyFilled(['category', 'brand', 'condition', 'sort']))
                                <a href="{{ route('product.index') }}" class="btn btn-light w-100 mt-2 border text-danger">Xóa bộ lọc</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: LƯỚI SẢN PHẨM --}}
            <div class="col-lg-9">
                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm product-card transition-hover">
                                {{-- Nhãn (Badge) --}}
                                <div class="position-absolute top-0 start-0 p-2 z-1">
                                    @if($product->condition == 'new')
                                        <span class="badge bg-success">Mới</span>
                                    @else
                                        <span class="badge bg-secondary">Đã qua sử dụng</span>
                                    @endif
                                </div>

                                {{-- Hình ảnh --}}
                                @php
                                    $primaryImg = $product->images->where('is_primary', true)->first();
                                    $imgSrc = $primaryImg ? asset($primaryImg->image_path) : asset('frontend/images/no-image.jpg');
                                @endphp
                                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                    <img src="{{ $imgSrc }}" class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                                </a>

                                {{-- Thông tin --}}
                                <div class="card-body d-flex flex-column">
                                    <small class="text-muted mb-1">{{ $product->brand->name ?? 'Khác' }}</small>
                                    <h6 class="card-title fw-bold text-truncate mb-2">
                                        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                                    </h6>

                                    <div class="mt-auto pt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-danger fw-bold fs-5">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fa-solid fa-magnifying-glass-minus fa-3x text-muted mb-3"></i>
                            <h5 class="fw-bold">Không tìm thấy sản phẩm nào!</h5>
                            <p class="text-muted">Vui lòng thử lại với các tiêu chí lọc khác.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Phân trang --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-hover { transition: all 0.3s ease; }
        .transition-hover:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    </style>
@endsection
